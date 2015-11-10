<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Controllers\Controller;
use AbuseIO\Models\Brand;
use yajra\Datatables\Datatables;


class BrandsController extends Controller
{
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $brands = Brand::all();

        return Datatables::of($brands)
            ->addColumn('actions', function ($brand) {
                $actions = \Form::open(['route' => ['admin.brands.destroy', $brand->id], 'method' => 'DELETE', 'class' => 'form-inline']);
                $actions .= ' <a href="brands/' . $brand->id .
                    '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> '.
                    trans('misc.button.show').'</a> ';
                $actions .= ' <a href="brands/' . $brand->id .
                    '/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> '.
                    trans('misc.button.edit').'</a> ';
                $actions .= \Form::button('<i class="glyphicon glyphicon-remove"></i> '. trans('misc.button.delete'), ['type' => 'submit', 'class' => 'btn btn-danger btn-xs']);
                $actions .= \Form::close();
                return $actions;
            })
            ->addColumn('logo', function ($brand) {
                $logo = '<img src="/admin/logo/' . $brand->id .'" height="40px"/>';
                return $logo;
            })
            ->make(true);
    }

    /*
      * Call the parent constructor to generate a base ACL
      */
    public function __construct()
    {
        parent::__construct();
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
