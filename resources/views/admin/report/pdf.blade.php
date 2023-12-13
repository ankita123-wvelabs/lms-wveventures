<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Leave - Report</title>
    <style>
        body{margin: 0; padding: 0; font-size: 16px; color: #000;}
        .headerlogo{text-align: center;}
        .headerdate{ padding:15px 0px }
        .headertitle{font-size: 30px; padding-bottom: 30px;}
        .table{}
        .listtbl{border:1px #000 solid;}
        .listtbl th,.listtbl td{border:1px #000 solid; padding: 5px;}
    </style>
</head>
  <body>
      
    <table width="100%" cellpadding="0" cellspacing="0" class="table" id="dataTable">
        <tr>
            <td class="headerlogo"><img src="{{ public_path('admin/images/login-logo.png') }}"></td>
        </tr>
        <tr>
            <td class="headerdate">Month : {{ $data['month'] }}</td>
        </tr>
        <tr>
            <td class="headerdate">Year : {{ $data['year'] }}</td>
        </tr>
        <tr>
            <td class="headertitle">Leaves Report</td>
        </tr>
        <tr>
            <td width="100%" align="center">
                <table width="100%" cellpadding="0" cellspacing="0" class="listtbl">
                   <thead>
                        <tr>
                            <th width="20%">Name</th>
                            <th width="40%">Leaves</th>
                            <th width="30%">Count</th>
                            <th width="30%">LWP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $key => $user)
                        <tr>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['leave_dates'] }}</td>
                            <td>{{ $user['leaves'] }}</td>
                            <td>{{ $user['lwps'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
  </body>
</html>