<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\BrandFormRequest;
use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use AbuseIO\Traits\Api;
use AbuseIO\Transformers\BrandTransformer;
use Exception;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Redirect;
use yajra\Datatables\Datatables;

/**
 * Class BrandsController.
 */
class BrandsController extends Controller
{
    use Api;

    /**
     * @param Manager $fractal
     * @param Request $request
     */
    public function __construct(Manager $fractal, Request $request)
    {
        parent::__construct();

        // initialize the Api methods
        $this->apiInit($fractal, $request);

        // is the logged in account allowed to execute an action on the Brand
        $this->middleware('checkaccount:Brand', ['except' => ['search', 'index', 'create', 'store', 'export', 'logo', 'apiIndex', 'apiShow', 'apiDestroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::paginate(10);

        return view('brands.index')
            ->with('brands', $brands)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        $brands = Brand::all();

        return $this->respondWithCollection($brands, new BrandTransformer());
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
        //in a normal account only show the active and the created

        if ($account->isSystemAccount()) {
            $brands = Brand::all();
        } else {
            // retrieve the active brand as a collection
            $active_brands = Brand::where('id', '=', $account->active_brand->id)->get();

            // retrieve the created accounts
            $created_brands = Brand::whereIn('id', $account->brands->pluck('id'))->get();

            $brands = $active_brands->merge($created_brands);
        }

        return Datatables::of($brands)
            ->addColumn(
                'status',
                function ($brand) use ($account) {
                    if ($account->brand->is($brand)) {
                        return trans('misc.active');
                    } else {
                        return trans('misc.inactive');
                    }
                }
            )
            ->addColumn(
                'actions',
                function ($brand) use ($account) {
                    $actions = \Form::open(
                        [
                            'route' => [
                                'admin.brands.destroy',
                                $brand->id,
                            ],
                            'method' => 'DELETE',
                            'class'  => 'form-inline',
                        ]
                    );
                    if (!$brand->isSystemBrand() or $account->isSystemAccount()) {
                        if (!$account->brand->is($brand)) {
                            $actions .= ' <a href="brands/'.$brand->id.
                                '/activate" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-play"></i> '.
                                trans('misc.button.activate').'</a> ';
                        }
                        $actions .= ' <a href="brands/'.$brand->id.
                            '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> '.
                            trans('misc.button.show').'</a> ';
                        $actions .= ' <a href="brands/'.$brand->id.
                            '/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> '.
                            trans('misc.button.edit').'</a> ';
                        $actions .= \Form::button(
                            '<i class="glyphicon glyphicon-remove"></i> '
                            .trans('misc.button.delete'),
                            [
                                'type'  => 'submit',
                                'class' => 'btn btn-danger btn-xs',
                            ]
                        );
                    }
                    $actions .= \Form::close();

                    return $actions;
                }
            )
            ->addColumn(
                'logo',
                function ($brand) {
                    $logo = '<img src="/admin/logo/'.$brand->id.'" width="60px"/>';

                    return $logo;
                }
            )
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::pluck('name', 'id');
        $templates = Brand::getDefaultMailTemplate();
        $templates['ash'] = Brand::getDefaultASHTemplate();

        return view('brands.create')
            ->with('account_selection', $accounts)
            ->with('selected', null)
            ->with('brand', null)
            ->with('ash_custom_template', false)
            ->with('mail_custom_template', false)
            ->with('templates', $templates)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BrandFormRequest $brandForm
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BrandFormRequest $brandForm)
    {
        $input = $brandForm->all();
        $account = $this->auth_user->account;

        $input['mail_custom_template'] = $input['mail_custom_template'] == 'true';
        $input['ash_custom_template'] = $input['ash_custom_template'] == 'true';

        if ($brandForm->hasFile('logo') && $brandForm->file('logo')->isValid()) {
            $input['logo'] = file_get_contents($brandForm->file('logo')->getRealPath());
        } else {
            return Redirect::route('admin.brands.create')
                ->withInput($input)
                ->withErrors(['logo' => 'Something went wrong, while uploading the logo']);
        }

        try {
            if (!$account->isSystemAccount()) {
                $input['creator_id'] = $account->id;
            }

            $brand = Brand::create($input);

            if (!$brand) {
                // when we can't save the brand, throw an exception
                throw new Exception("Couldn't create new brand");
            }
        } catch (Exception $e) {
            return Redirect::route('admin.brands.create')
                ->withInput($input)
                ->with('message', $e->getMessage());
        }

        return Redirect::route('admin.brands.index')
            ->with('message', 'Brand has been created');
    }

    /**
     * Store a newly created resource in storage.
     * todo.
     *
     * @param BrandFormRequest $brandForm
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(BrandFormRequest $brandForm)
    {
        $input = $brandForm->all();
        $account = $this->api_account;

        if ($brandForm->hasFile('logo') && $brandForm->file('logo')->isValid()) {
            $input['logo'] = file_get_contents($brandForm->file('logo')->getRealPath());
        } else {
            return Redirect::route('admin.brands.create')
                ->withInput($input)
                ->withErrors(['logo' => 'Something went wrong, while uploading the logo']);
        }

        try {
            if (!$account->isSystemAccount()) {
                $input['creator_id'] = $account->id;
            }

            $brand = Brand::create($input);

            if (!$brand) {
                // when we can't save the brand, throw an exception
                throw new Exception("Couldn't create new brand");
            }
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
     * @param Brand $brand
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return view('brands.show')
            ->with('brand', $brand)
            ->with('creator', $brand->creator)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Brand $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiShow(Brand $brand)
    {
        return $this->respondWithItem($brand, new BrandTransformer());
    }

    /**
     * return the logo as an image.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
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
     * @param Brand $brand
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        $accounts = Account::pluck('name', 'id');
        $templates = [
            'plain_mail' => $brand->mail_template_plain,
            'html_mail'  => $brand->mail_template_html,
            'ash'        => $brand->ash_template,
        ];

        return view('brands.edit')
            ->with('account_selection', $accounts)
            ->with('selected', $brand->creator_id)
            ->with('brand', $brand)
            ->with('auth_user', $this->auth_user)
            ->with('ash_custom_template', $brand->ash_custom_template)
            ->with('mail_custom_template', $brand->mail_custom_template)
            ->with('templates', $templates);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BrandFormRequest $brandForm
     * @param Brand            $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BrandFormRequest $brandForm, Brand $brand)
    {
        $input = $brandForm->all();

        $input['mail_custom_template'] = $input['mail_custom_template'] == 'true';
        $input['ash_custom_template'] = $input['ash_custom_template'] == 'true';

        if ($brandForm->hasFile('logo') && $brandForm->file('logo')->isValid()) {
            $input['logo'] = file_get_contents($brandForm->file('logo')->getRealPath());
        }

        $brand->update($input);

        return Redirect::route('admin.brands.show', $brand->id)
            ->with('message', 'Brand has been updated.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BrandFormRequest $brandForm
     * @param Brand            $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apiUpdate(BrandFormRequest $brandForm, Brand $brand)
    {
        $input = $brandForm->all();

        //        if ($brandForm->hasFile('logo') && $brandForm->file('logo')->isValid()) {
        //            $input['logo'] = file_get_contents($brandForm->file('logo')->getRealPath());
        //        }
        //
        $brand->update($input);

        return $this->respondWithItem($brand, new BrandTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Brand $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Brand $brand)
    {
        if ($brand->canDelete()) {
            $brand->delete();
        } else {
            return Redirect::back()
                ->with('message', "Can't delete an active and/or system brand.");
        }

        return Redirect::route('admin.brands.index')
            ->with('message', 'Brand has been deleted.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiDestroy($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return $this->errorNotFound('Brand Not Found');
        }

        if (!$brand->canDelete()) {
            return $this->errorForbidden("Can't Delete an active and/or system brand.");
        }
        $brand->delete();

        return $this->respondWithItem($brand, new BrandTransformer());
    }

    /**
     * Set the brand as the active brand on the current account.
     *
     * @param Brand $brand
     *
     * @return Redirect
     */
    public function activate(Brand $brand)
    {
        $account = $this->auth_user->account;
        $account->brand_id = $brand->id;
        $account->save();

        return Redirect::route('admin.brands.index', $brand->id)
            ->with('message', 'Brand has been activated.');
    }
}
