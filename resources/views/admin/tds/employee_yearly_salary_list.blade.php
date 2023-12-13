@if(sizeof($salaries) > 0)
<div class="panel-body">
    <div class="adv-table">
        <table  class="display table table-bordered table-striped" id="dataTable">
            <thead>
                <tr>
                    <th width="20%">Month</th>
                    <th width="15%">Salary</th>
                    <th width="15%">Status</th>
                    <th width="15%">View</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salaries as $key => $salary)
                <tr>
                    <td>{{ $salary['month'] }}</td>
                    <td>{{ number_format(round($salary['amount']),2) }}</td>
                    <td style="color: {{ $salary['status'] == 2 ? 'Red' : ($salary['status'] == 0 ? 'Orange' : 'Green')}}">{{ $salary['status'] == 2 ? 'To be Processing' : ($salary['status'] == 0 ? 'Processing' : 'Processed') }}</td>
                    <td><a href="{{ route('admin.tds.salary.slip.view', $salary['month']) }}" target="_blank" />View</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="panel-body">
    <div class="adv-table">
    No data available
    </div>
</div>
@endif