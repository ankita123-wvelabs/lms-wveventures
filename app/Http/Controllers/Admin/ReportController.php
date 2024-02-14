<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InOut;
use App\Models\Leave;
use App\Models\Holiday;
use App\Models\LWP;
use App\Models\FeedBack;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $request['month'] = date('m') - 1;

        $request['year'] = date('Y');
        
        $users = User::with('getLeaves')->get();
        
        foreach ($users as $key => $user) {
            $user['leaves'] = 0;
            $user['leave_dates'] = 'No Leaves';
            $user['lwps'] = 0;
            
            foreach ($user['getLeaves'] as $key => $value) {
                $dates = explode(',', $value['date']);
                $month_array = [];
                
                foreach ($dates as $key => $date) {
                    $month = date('m', strtotime($date));
                    $year = date('Y', strtotime($date));

                    $month_array[$month][] = $date;

                    if($month == $request['month'] && $request['year'] == $year) {
                        $user['leave_dates'] = sizeof($month_array[$month]) > 1 ? $month_array[$month][0] .' To ' .end($month_array[$month]) : $month_array[$month][0];
                        $user['leaves'] = sizeof($month_array[$month]);
                        $user['lwps'] = LWP::where([ 'user_id' => $user['id'],'month' => $request['month'], 'year' => $request['year']])->value('count');
                    }
                }
            }
        }

        $last = 2000;
        
        $years = [];

        for($i = $request['year']; $i > $last; $i--) { 
            $years[] = $i;
        }

        $months = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun',  
                    '07' => 'July', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];

        return view('admin.report.index', compact('years', 'users', 'request', 'months'));
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

    public function getReport(Request $request)
    {
        $data = $request->except('_token');

        \Session::put('report', $data);

        $users = User::with('getLeaves')->paginate(20);
        
        foreach ($users as $key => $user) {
            $user['leaves'] = 0;
            $user['leave_dates'] = 'No Leaves';
            $user['lwps'] = 0;
            
            foreach ($user['getLeaves'] as $key => $value) {
                $dates = explode(',', $value['date']);
                $month_array = [];

                foreach ($dates as $key => $date) {
                    $month = date('m', strtotime($date));
                    $year = date('Y', strtotime($date));

                    $month_array[$month][] = $date;

                    if($month == $data['month'] && $data['year'] == $year) {
                        $user['leave_dates'] = sizeof($month_array[$month]) > 1 ? $month_array[$month][0] .' To ' .end($month_array[$month]) : $month_array[$month][0];
                        $user['leaves'] = sizeof($month_array[$month]);
                        $user['lwps'] = LWP::where([ 'user_id' => $user['id'],'month' => $data['month'], 'year' => $data['year']])->value('count');
                    }
                }
            }
        }
        
        $html = view('admin.report.table_view', compact('users'))->render();

        return response()->json(['data' => $html, 'code' => 200, 'success' => true]);
    }

    public function generateReport(Request $request) {
        
        $data = \Session::get('report');

        if($data == null) {
            $data['month'] = date('m') - 1;

            $data['year'] = date('Y');
        }
        // \Session::destroy();
        
        $users = User::with('getLeaves')->paginate(20);
        
        foreach ($users as $key => $user) {
            $user['leaves'] = 0;
            $user['leave_dates'] = 'No Leaves';
            $user['lwps'] = 0;
            
            foreach ($user['getLeaves'] as $key => $value) {
                $dates = explode(',', $value['date']);
                $month_array = [];

                foreach ($dates as $key => $date) {
                    $month = date('m', strtotime($date));
                    $year = date('Y', strtotime($date));

                    $month_array[$month][] = $date;

                    if($month == $data['month'] && $data['year'] == $year) {
                        $user['leave_dates'] = sizeof($month_array[$month]) > 1 ? $month_array[$month][0] .' To ' .end($month_array[$month]) : $month_array[$month][0];
                        $user['leaves'] = sizeof($month_array[$month]);
                        $user['lwps'] = LWP::where([ 'user_id' => $user['id'],'month' => $data['month'], 'year' => $data['year']])->value('count');
                    }
                }
            }
        }
        \PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        $pdf = \PDF::loadView('admin.report.pdf', compact('users', 'data'));

        return $pdf->download('leave-report.pdf');
    }


    public function feedBack(Request $request) {
        if ($request->ajax()) {
            $where_str = '1 = ?';
            $where_params = [1];

            if ($request->has('sSearch')) {
                $search = $request->get('sSearch');
                $where_str .= " and ( subject like \"%{$search}%\""
                    . ")";
            }

            $data = FeedBack::select('feed_backs.id', 'subject', 'description', 'users.name')
                ->leftjoin('users', 'users.id', 'feed_backs.user_id')
                ->orderBy('id', 'desc')
                ->whereRaw($where_str, $where_params);

            $data_count = FeedBack::select('feed_backs.id')
                ->leftjoin('users', 'users.id', 'feed_backs.user_id')
                ->orderBy('id', 'desc')
                ->whereRaw($where_str, $where_params)
                ->count();

            $columns = ['feed_backs.id', 'subject', 'description', 'users.name'];

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

        return view('admin.feedback.index');
    }

    public function attendence(Request $request) {
        
        $temp = User::get();

        $today = date('Y-m-d');
        $users = [];

        $date = \Carbon\Carbon::now()->setTimezone('asia/kolkata');
        
        $date_array = $date->toArray();
        
        $type = 'today';
        foreach ($temp as $key => $user) {
            $local_temp = [];
            $attendence = InOut::where('user_id', $user['id'])->where('date', $today)->orderBy('date', 'asc')->first();
            
            if(isset($attendence) && $attendence['timing'] != null) {

            $attendence['timing'] = unserialize($attendence['timing']);
            $newTime = array();
            foreach ($attendence['timing'] as $key => $value) {
                $newTime[] = $this->convertTimeToUSERzone($value,"UTC","asia/kolkata");
            }
            $attendence['timing'] = $newTime;
            $counter = 1;
            $total_time = 0;
                foreach ($attendence['timing'] as $key => $time) {
                    
                    if($counter % 2 != 0) {
                        if(array_key_exists($key + 1, $attendence['timing'])) {
                            $time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
                        } else {
                            $time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
                        }
                        $total_time += $time;
                    }
                    $counter++;
                }
                
                $attendence['present_time'] = date('H:i',$total_time);
                // if(count($attendence['timing']) % 2 != 0){
                //     $attendence['present_time'] = "-";
                // }
                $attendence['date'] = $today;
                // $attendence['total_time'] = '8:15';
                // $attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
            }
            $local_temp['user'] = $user;
            $local_temp['attendence'][] = $attendence;
            $users[] = $local_temp;
        }
        // dd($users);
        return view('admin.attendence.create', compact('users', 'type'));
    }

    public function attendenceReport(Request $request) {

        $data = $request->all();

        if($data['user'] == 'all') {
            $temp = User::orderBy('emp_id', 'asc')->get();
        } else {
            $temp = User::where('id',$data['user'])->get();
        }

        $today = date('Y-m-d');

        $date = \Carbon\Carbon::now()->setTimezone('asia/kolkata');
        
        $date_array = $date->toArray();
        $type = $data['type'];
        $grandTotalHrs = ($data['user'] == 'all' || $data['type'] == 'today') ? null : 0;

        $users = [];
        foreach ($temp as $key => $value) {
            switch ($type) {
                case 'today':
                    $local_temp = [];
                    $attendence = InOut::where('user_id', $value['id'])->where('date', $today)->orderBy('date', 'asc')->first();
                    if($value['id'] == 57){
                        //dd($value['name']);
                    }
                    if(isset($attendence) && $attendence['timing'] != null) {

                    $attendence['timing'] = unserialize($attendence['timing']);

                    $newTime = array();
                    foreach ($attendence['timing'] as $key => $timingValue) {
                        $newTime[] = $this->convertTimeToUSERzone($timingValue,"UTC","asia/kolkata");
                    }
                    $attendence['timing'] = $newTime;
                    $counter = 1;
                    $total_time = 0;
                        foreach ($attendence['timing'] as $key => $timedate) {
                            
                            if($counter % 2 != 0) {
                                if(array_key_exists($key + 1, $attendence['timing'])) {
                                    $time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
                                } else {
                                    $time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
                                    //dd($date_array['hour'].':'.$date_array['minute']."||".$attendence['timing'][$key]);
                                }
                                $total_time += $time;
                            }
                            $counter++;
                        }
                        
                        $attendence['present_time'] = date('H:i',$total_time);
                        // if(count($attendence['timing']) % 2 != 0){
                        //     $attendence['present_time'] = "-";
                        // }
                        $attendence['date'] = $today;
                        // $attendence['total_time'] = '8:15';
                        // $attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
                    }
                    $local_temp['user'] = $value;
                    $local_temp['attendence'][] = $attendence;
                    $users[] = $local_temp;
                    break;

                case 'current_week':
                    $monday = date( 'Y-m-d', strtotime( 'monday this week' ) );
                    $friday = date( 'Y-m-d', strtotime( 'friday this week' ) );
                    $local_temp = [];
                    $attendences = InOut::where('user_id', $value['id'])->whereBetween('date', [$monday, $friday])->orderBy('date', 'asc')->get();

                     if($attendences) {
                        foreach ($attendences as $attendence) {
                            $date = $attendence['date'];
                            if(isset($attendence) && $attendence['timing'] != null) {

                            $attendence['timing'] = unserialize($attendence['timing']);
                            $newTime = array();
                            foreach ($attendence['timing'] as $key => $timingValue) {
                                $newTime[] = $this->convertTimeToUSERzone($timingValue,"UTC","asia/kolkata");
                            }
                            $attendence['timing'] = $newTime;
                            // if($date == "2021-04-08"){
                            //     dd($attendence['timing']);
                            // }
                            $counter = 1;
                            $total_time = 0;
                                foreach ($attendence['timing'] as $key => $time) {

                                    if($counter % 2 != 0) {
                                        if(array_key_exists($key + 1, $attendence['timing'])) {
                                            $time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
                                        } else {
                                            $time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
                                        }
                                        $total_time += $time;
                                    }
                                    $counter++;
                                }
                                
                                $attendence['date'] = $date;
                                $attendence['present_time'] = date('H:i',$total_time);
                                if(count($attendence['timing']) % 2 != 0 && $attendence['date'] != $today){
                                    $attendence['present_time'] = "-";
                                }
                                // $attendence['total_time'] = '8:15';
                                // $attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
                            }
                            $local_temp['attendence'][] = $attendence;
                        }
                        $local_temp['user'] = $value;
                        if(sizeof($attendences) > 0) {
                            $users[] = $local_temp;
                        }
                        if($data['user'] != 'all' && $data['user'] > 0){
                            foreach ($users as &$entries) {
                                $entireAttendence = $entries['attendence'];

                                $totalSeconds = 0;

                                foreach ($entireAttendence as $item) {
                                    $presentTime = $item['present_time'];
                                    if($presentTime == '-') {
                                        $presentTime = '00:00';
                                    }
                                    list($hours, $minutes) = explode(':', $presentTime);
                                
                                    // Convert hours and minutes to seconds
                                    $totalSeconds += $hours * 3600 + $minutes * 60;
                                }

                                // Convert total seconds to hours and minutes
                                $totalHours = floor($totalSeconds / 3600);
                                $totalMinutes = floor(($totalSeconds % 3600) / 60);
                                
                                // Format the total time as "hh:mm"
                                $grandTotalHrs = sprintf('%02d:%02d', $totalHours, $totalMinutes);
                                // return $formattedTotalTime;
                            }
                        }
                    }
                    break;

                case 'previous_week':
                    $monday = date( 'Y-m-d', strtotime( 'monday last week' ) );
                    $friday = date( 'Y-m-d', strtotime( 'friday last week' ) );

                    $local_temp = [];
                    $attendences = InOut::where('user_id', $value['id'])->whereBetween('date', [$monday, $friday])->orderBy('date', 'asc')->get();

                    if($attendences) {
                        foreach ($attendences as $attendence) {
                            $date = $attendence['date'];
                            if(isset($attendence) && $attendence['timing'] != null) {

                            $attendence['timing'] = unserialize($attendence['timing']);
                            $newTime = array();
                            foreach ($attendence['timing'] as $key => $timingValue) {
                                $newTime[] = $this->convertTimeToUSERzone($timingValue,"UTC","asia/kolkata");
                            }
                            $attendence['timing'] = $newTime;
                            $counter = 1;
                            $total_time = 0;
                                foreach ($attendence['timing'] as $key => $timing) {
                                    
                                    if($counter % 2 != 0) {
                                        if(array_key_exists($key + 1, $attendence['timing'])) {
                                            $time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
                                        } else {
                                            $time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
                                        }
                                        $total_time += $time;
                                    }
                                    $counter++;
                                }
                                
                                $attendence['date'] = $date;
                                $attendence['present_time'] = date('H:i',$total_time);
                                if(count($attendence['timing']) % 2 != 0){
                                    $attendence['present_time'] = "-";
                                }
                                // $attendence['total_time'] = '8:15';
                                // $attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
                            }
                            $local_temp['attendence'][] = $attendence;
                        }
                        $local_temp['user'] = $value;
                        if(sizeof($attendences) > 0) {
                            $users[] = $local_temp;
                        }
                        if($data['user'] != 'all' && $data['user'] > 0){
                            foreach ($users as &$entries) {
                                $entireAttendence = $entries['attendence'];
    
                                $totalSeconds = 0;
    
                                foreach ($entireAttendence as $item) {
                                    $presentTime = $item['present_time'];
                                    if($presentTime == '-') {
                                        $presentTime = '00:00';
                                    }
                                    list($hours, $minutes) = explode(':', $presentTime);
                                
                                    // Convert hours and minutes to seconds
                                    $totalSeconds += $hours * 3600 + $minutes * 60;
                                }
    
                                // Convert total seconds to hours and minutes
                                $totalHours = floor($totalSeconds / 3600);
                                $totalMinutes = floor(($totalSeconds % 3600) / 60);
                                
                                // Format the total time as "hh:mm"
                                $grandTotalHrs = sprintf('%02d:%02d', $totalHours, $totalMinutes);
                                // return $formattedTotalTime;
                            }
                        }
                    }
                    break;

                case 'previous_month':
                    
                    $local_temp = [];

                    $current_month = date('n');
                
                    if($current_month == 1) {
                        $month = 12;
                        $year = $year - 1;
                    }

                    $month = $current_month - 1;
                    $year = date('Y'); // Year in 4 digit 2009 format.

                    $attendences = InOut::where('user_id', $value['id'])->where('month', $month)->where('year', $year)->orderBy('date', 'asc')->get();
                    // dd($attendences);
                    $monthly_hours = 0;

                    if($attendences) {
                        foreach ($attendences as $attendence) {
                            $date = $attendence['date'];
                            if(isset($attendence) && $attendence['timing'] != null) {

                            $attendence['timing'] = unserialize($attendence['timing']);
                            $newTime = array();
                            foreach ($attendence['timing'] as $key => $timingValue) {
                                $newTime[] = $this->convertTimeToUSERzone($timingValue,"UTC","asia/kolkata");
                            }
                            $attendence['timing'] = $newTime;
                            $counter = 1;
                            $total_time = 0;
                                foreach ($attendence['timing'] as $key => $timing) {
                                    
                                    if($counter % 2 != 0) {
                                        if(array_key_exists($key + 1, $attendence['timing'])) {
                                            $time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
                                        } else {
                                            $time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
                                        }
                                        $total_time += $time;
                                    }
                                    $counter++;
                                }
                                
                                $attendence['date'] = $date;
                                $attendence['present_time'] = date('H:i',$total_time);
                                if(count($attendence['timing']) % 2 != 0){
                                    $attendence['present_time'] = "-";
                                }
                                // $attendence['total_time'] = '8:15';
                                // $attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
                            }
                            $local_temp['attendence'][] = $attendence;
                        }
                        $local_temp['user'] = $value;
                        if(sizeof($attendences) > 0) {
                            $users[] = $local_temp;
                        }

                        if($data['user'] != 'all' && $data['user'] > 0){
                            foreach ($users as &$entries) {
                                $entireAttendence = $entries['attendence'];

                                $totalSeconds = 0;

                                foreach ($entireAttendence as $item) {
                                    $presentTime = $item['present_time'];
                                    if($presentTime == '-') {
                                        $presentTime = '00:00';
                                    }
                                    list($hours, $minutes) = explode(':', $presentTime);
                                
                                    // Convert hours and minutes to seconds
                                    $totalSeconds += $hours * 3600 + $minutes * 60;
                                }

                                // Convert total seconds to hours and minutes
                                $totalHours = floor($totalSeconds / 3600);
                                $totalMinutes = floor(($totalSeconds % 3600) / 60);
                                
                                // Format the total time as "hh:mm"
                                $grandTotalHrs = sprintf('%02d:%02d', $totalHours, $totalMinutes);
                                // return $formattedTotalTime;
                            }
                        }
                    }
                    
                    break;

                case 'current_month':
                    $date123 = \Carbon\Carbon::now();
        
                    $date_array123 = $date123->toArray();
                    $month = date('n'); // Month ID, 1 through to 12.
                    $year = date('Y'); // Year in 4 digit 2009 format.
                    
                    $local_temp = [];
                    $attendences = InOut::where('user_id', $value['id'])->where('month', $month)->where('year', $year)->orderBy('date', 'asc')->get();
                      
                    $monthly_hours = 0;
                    if($attendences) {
                        foreach ($attendences as $attendence) {
                            $date = $attendence['date'];
                            if(isset($attendence) && $attendence['timing'] != null) {

                            $attendence['timing'] = unserialize($attendence['timing']);
                            $newTime = array();
                            foreach ($attendence['timing'] as $key => $timingValue) {
                                $newTime[] = $this->convertTimeToUSERzone($timingValue,"UTC","asia/kolkata");
                            }
                            $attendence['timing'] = $newTime;
                            $counter = 1;
                            $total_time = 0;
                                foreach ($attendence['timing'] as $key => $timing) {
                                    
                                    if($counter % 2 != 0) {
                                        if(array_key_exists($key + 1, $attendence['timing'])) {
                                            $time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
                                        } else {
                                            $time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
                                        }
                                        $total_time += $time;
                                    }
                                    $counter++;
                                }
                                
                                $attendence['date'] = $date;
                                $attendence['present_time'] = date('H:i',$total_time);
                                if(count($attendence['timing']) % 2 != 0 && $attendence['date'] != $today){
                                    $attendence['present_time'] = "-";
                                }
                                // $attendence['total_time'] = '8:15';
                                // $attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
                            }
                            $local_temp['attendence'][] = $attendence;
                        }
                        $local_temp['user'] = $value;
                        if(sizeof($attendences) > 0) {
                            $users[] = $local_temp;
                        }

                        if($data['user'] != 'all' && $data['user'] > 0){
                            foreach ($users as &$entries) {
                                $entireAttendence = $entries['attendence'];
                                
                                $totalSeconds = 0;
                                
                                foreach ($entireAttendence as $item) {
                                    if (isset($item['present_time']) && $item['present_time'] != '-') {
                                        $presentTime = $item['present_time'];
                                        if($presentTime == '-') {
                                            $presentTime = '00:00';
                                        }
                                        list($hours, $minutes) = explode(':', $presentTime);
                        
                                        // Convert hours and minutes to seconds
                                        $totalSeconds += $hours * 3600 + $minutes * 60;
                                    }
                                }

                                // Convert total seconds to hours and minutes
                                $totalHours = floor($totalSeconds / 3600);
                                $totalMinutes = floor(($totalSeconds % 3600) / 60);
                                
                                // Format the total time as "hh:mm"
                                $grandTotalHrs = sprintf('%02d:%02d', $totalHours, $totalMinutes);
                                // return $formattedTotalTime;
                            }
                        }
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
        }

        $html = view('admin.attendence.attendence', compact('users', 'type', 'grandTotalHrs'))->render();

        return response()->json(['data' => $html, 'code' => 200, 'success' => true]);
    }

    //this function converts string from UTC time zone to current user timezone
    public static function convertTimeToUSERzone($time, $fromUserTimezone="UTC", $toUserTimezone="asia/kolkata", $format = 'H:i'){
        if(empty($time)){
            return '';
        }
        $tzFrom         = new \DateTimeZone($fromUserTimezone);
        $tzTo           = new \DateTimeZone($toUserTimezone);

        $origTime       = new \DateTime($time, $tzFrom);
        $newTime        = $origTime->setTimezone($tzTo);

        return $newTime->format('H:i');
    }

    public static function AddPlayTime($times) {
        $minutes = 0; //declare minutes either it gives Notice: Undefined variable
        // loop throught all the times
        foreach ($times as $time) {
            list($hour, $minute) = explode(':', $time);
            $minutes += $hour * 60;
            $minutes += $minute;
        }

        $hours = floor($minutes / 60);
        $minutes -= $hours * 60;

        // returns the time already formatted
        return sprintf('%02d:%02d', $hours, $minutes);
    }

}
