<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthDeviceRequest;
use App\Jobs\AuthDeviceJob;
use App\Models\AuthDevice;

class AuthDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if ($request->ajax()) {
            $where_str = '1 = ?';
            $where_params = [1];

            if ($request->has('sSearch')) {
                $search = $request->get('sSearch');
                $where_str .= " and ( device_name like \"%{$search}%\""
                    . ")";
            }

            $data = AuthDevice::select('auth_devices.id', 'device_id', 'device_name', 'status')
                ->whereRaw($where_str, $where_params);

            $data_count = AuthDevice::select('id')
                ->whereRaw($where_str, $where_params)
                ->count();

            $columns = ['auth_devices.id', 'device_id', 'device_name', 'status'];

            if ($request->has('iDisplayStart') && $request->get('iDisplayLength') != '-1') {
                $data = $data->take($request->get('iDisplayLength'))->skip($request->get('iDisplayStart'));
            }

            if ($request->has('iSortCol_0')) {
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $data = $data->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $data = $data->get();

            $response['iTotalDisplayRecords'] = $data_count;
            $response['iTotalRecords'] = $data_count;

            $response['sEcho'] = intval($request->get('sEcho'));

            $response['aaData'] = $data;

            return $response;
        }

        return view('admin.auth_device.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.auth_device.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthDeviceRequest $request)
    {
        $data = $request->all();

        dispatch(new AuthDeviceJob($data));

        return redirect()->route('admin.auth-devices.index')->with('message', 'Record saved successfully')
            ->with('type', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = AuthDevice::find($id);

        return view('admin.auth_device.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AuthDeviceRequest $request, $id)
    {
        $data = $request->all();

        dispatch(new AuthDeviceJob($data));

        return redirect()->route('admin.auth-devices.index')->with('message', 'Record saved successfully')
            ->with('type', 'success');
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

    public function delete(Request $request) {
        $id = $request->get('id');

        if (!is_array($id)) {

            $id = array($id);
        }
        AuthDevice::whereIn('id', $id)->delete();

        return back()->with('message', 'Record deleted successfully.');
    }

    /**
     * Active/Inactive Device.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request) {
        
        $id = $request->get('id');

        if (!is_array($id)) {
            $id = array($id);
        }
        
        foreach ($id as $key => $value) {

            $auth_device = AuthDevice::where('id', $value)->first();

            AuthDevice::where('id', $auth_device['id'])->update(['status' => $request->status]);
        }

        return redirect()->back()->with('message', 'Record saved successfully')
            ->with('type', 'success');
    }
}
