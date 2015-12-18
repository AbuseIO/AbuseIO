<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\BrandFormRequest;
use Illuminate\Filesystem\Filesystem;
use DB;
use Exception;
use AbuseIO\Http\Requests;
use AbuseIO\Models\Brand;
use Illuminate\Http\Response;
use yajra\Datatables\Datatables;
use Redirect;

class BrandsController extends Controller
{
    /*
     * Call the parent constructor to generate a base ACL
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $brands = Brand::paginate(10);

        return view('brands.index')
            ->with('brands', $brands)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $account = $this->auth_user->account;

        //return all brands when we are in the system account
        //in a normal account only show the current linked one

        if ($account->isSystemAccount()) {
            $brands = Brand::all();
        } else {
            // retrieve it as a collection
            $brands = Brand::where('id', '=', $account->brand->id)->get();
        }

        return Datatables::of($brands)
            ->addColumn(
                'actions',
                function ($brand) {
                    $actions = \Form::open(
                        [
                            'route' => [
                                'admin.brands.destroy',
                                $brand->id
                            ],
                            'method' => 'DELETE',
                            'class' => 'form-inline'
                        ]
                    );
                    $actions .= ' <a href="brands/' . $brand->id .
                        '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> '.
                        trans('misc.button.show').'</a> ';
                    $actions .= ' <a href="brands/' . $brand->id .
                        '/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> '.
                        trans('misc.button.edit').'</a> ';
                    $actions .= \Form::button(
                        '<i class="glyphicon glyphicon-remove"></i> '
                        . trans('misc.button.delete'),
                        [
                            'type' => 'submit',
                            'class' => 'btn btn-danger btn-xs'
                        ]
                    );
                    $actions .= \Form::close();
                    return $actions;
                }
            )
            ->addColumn(
                'logo',
                function ($brand) {
                    $logo = '<img src="/admin/logo/' . $brand->id .'" height="40px"/>';
                    return $logo;
                }
            )
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('brands.create')
            ->with('brand', null)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BrandFormRequest $request
     * @return mixed
     */
    public function store(BrandFormRequest $brandForm)
    {
        $input = $brandForm->all();
        $account = $this->auth_user->account;

        if ($brandForm->hasFile('logo') && $brandForm->file('logo')->isValid()) {
            $errors = [];

            if (!Brand::checkUploadedLogo($brandForm->file('logo'), $errors)) {
                return Redirect::route('admin.brands.create')
                    ->withInput($input)
                    ->withErrors(['logo' => $errors]);
            }

            // all ok
            $input['logo'] = file_get_contents($brandForm->file('logo')->getRealPath());

        } else {
            return Redirect::route('admin.brands.create')
                ->withInput($input)
                ->withErrors(['logo' => 'Something went wrong, while uploading the logo']);
        }

        try {
            // begin transaction pass both $input and $account to the closure
            DB::transaction(
                function () use ($input, $account) {

                    $brand = Brand::create($input);
                    if (!$brand) {
                        // when we can't save the brand, throw an exception
                        throw new Exception("Couldn't create new brand");
                    }

                    // if our current account isn't the system account,
                    // link the new brand to the current account
                    if (!$account->isSystemAccount()) {
                        $account->brand_id = $brand->id;
                        $result = $account->save();

                        // when we can't save the account, throw an exception
                        // DB::transaction will automatically rollback
                        if (!$result) {
                            throw new Exception('Something went wrong, while linking the brand to the account');
                        }
                    }
                }
            );
        } catch (Exception $e) {
            return Redirect::route('admin.brands.create')
                ->withInput($input)
                ->with('message', $e->getMessage());
        }

        return Redirect::route('admin.brands.index')
            ->with('message', 'Brand has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Brand $brand)
    {
        return view('brands.show')
            ->with('brand', $brand)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * return the logo as an image
     *
     * @param int $id
     * @return Response
     */
    public function logo($id)
    {
        $brand = Brand::find($id);

        // if the brand with the specified id doesn't exist return nothing
        if (!$brand) {
            return false;
        }

        // get the mime type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimetype = $finfo->buffer($brand->logo);

        return response($brand->logo)
            ->header('Content-Type', $mimetype);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Brand $brand)
    {
        // may we edit this brand (is the brand connected to our account)
        if (!$brand->mayEdit($this->auth_user)) {
            return Redirect::route('admin.brands.show', $brand->id)
                ->with('message', 'User is not authorized to edit this brand.');
        }

        // if we are authorized, but the brand is the default brand and we
        // are not part of the system account, we probably want to create a new
        // brand instead
        if ($brand->isDefault() && !$this->auth_user->account->isSystemAccount()) {
            return Redirect::route('admin.brands.create');
        }

        return view('brands.edit')
            ->with('brand', $brand)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BrandFormRequest $request
     * @param Brand $brand
     * @return mixed
     */
    public function update(BrandFormRequest $brandForm, Brand $brand)
    {
        // may we edit this brand
        if (!$brand->mayEdit($this->auth_user)) {
            return Redirect::route('admin.brands.show', $brand->id)
                ->with('message', 'User is not authorized to edit this brand.');
        }

        $input = $brandForm->all();

        if ($brandForm->hasFile('logo') && $brandForm->file('logo')->isValid()) {
            $errors = [];

            if (!Brand::checkUploadedLogo($brandForm->file('logo'), $errors)) {
                return Redirect::route('admin.brands.edit', $brand->id)
                    ->withErrors(['logo' => $errors]);
            }

            // all ok
            $filesystem = new Filesystem;
            $input['logo'] = $filesystem->get($brandForm->file('logo')->getRealPath());

        }

        $brand->update($input);

        return Redirect::route('admin.brands.show', $brand->id)
            ->with('message', 'Brands has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
