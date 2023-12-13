<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>SALARY SLIP</title>
	<style type="text/css">
		body{margin: 0; padding: 0; font-family: arial,san-serif; font-family: 12px; color: #333;}
		h2{margin: 0; padding: 10px 0 15px; color: #219BFF;}
		p{margin: 0; padding: 0 0 15px;}
		.employeeinfo tr td,.employeeinfo tr th{padding:10px;}
		.employeeinfo tr th{background:#219BFF; color: #fff; text-align: left;}
	</style>
</head>

<body>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td align="center">
					<table width="800" border="0" cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td height="20"></td>
							</tr>
							<tr>
								<td>
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
									  <tbody>
										<tr>
											<td width="30%" valign="middle"><img src="{{ asset('admin/images/logo2.png') }}" alt="Logo"></td>
											<td width="70%">
												<h2>Western Virason Enterprise LLP</h2>
												<p>206 Safal Prelude, Corporate Road, Satellite, Ahmedabad 380015 | wvelabs.com | +91-8780850513</p>
											</td>
										</tr>
									  </tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td height="20"></td>
							</tr>
							<tr>
								<td>
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="employeeinfo">
										<tbody>
											<tr>
												<td width="25%"><strong>Employee Name:</strong></td>
												<td width="25%">{{ ucfirst($user['name']) }}</td>
												<td width="25%"><strong>Designation:</strong></td>
												<td width="25%">{{ ucfirst($user['position']) }}</td>
											</tr>
											<tr>
												<td><strong>Employee Address:</strong></td>
												<td>{{ isset($user['address']) ? $user['address'] : '-' }}</td>
												<td><strong>Salary Date:</strong></td>
												<td>10-01-2020</td>
											</tr>
											<tr>
												<td><strong>Employee ID:</strong></td>
												<td>{{ $user['emp_id'] }}</td>
												<td><strong>Salary Month:</strong></td>
												<td>{{ $user['salary_month'] }} {{ $current_year }}</td>
											</tr>
											<tr>
												<td><strong>Employee Contact:</strong></td>
												<td>{{ isset($user['phone']) ? $user['phone'] : '-' }}</td>
												<td><strong>PAN:</strong></td>
												<td>{{ isset($user['pan']) ? $user['pan'] : '-' }}</td>
											</tr>
											<tr>
												<td><strong>No. of Days Present:</strong></td>
												<td>{{ $user['present_days'] }}</td>
												<td><strong>No. of Working Days:</strong></td>
												<td>{{ sizeof($workdays) }}</td>
											</tr>
											<tr>
												<td><strong>LWP:</strong></td>
												<td>{{ ($lwp)?$lwp['count']:0 }}</td>
												<td><strong>Leave Balance:</strong></td>
												<td>{{ $user['leave_balance'] }}</td>
											</tr>
										</tbody>
									</table>

								</td>
							</tr>
							<tr>
								<td height="25"></td>
							</tr>
							<tr>
								<td>
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="employeeinfo">
										<tbody>
											<tr>
												<th colspan="2">Earnings</th>
												<th colspan="2">Deductions</th>
											</tr>
											<tr>
												<td width="25%">Basic Salary</td>
												<td width="25%">{{ number_format($user['salary'],2) }}</td>
												<td width="25%">Professional Tax</td>
												<td width="25%">200.00 </td>
											</tr>
											<tr>
												<td>House Rent Allowance</td>
												<td>9,999.00</td>
												<td>TDS</td>
												<td>{{ number_format(round($user['monthly_tds']),2) }}</td>
											</tr>
											<tr>
												<td>Special Allowance</td>
												<td>9,999.00</td>
												<td></td>
												<td></td>
											</tr>
											<tr>
												<td>Year-End Bonus</td>
												<td>9,999.00</td>
												<td></td>
												<td></td>
											</tr>
											<tr>
												<td height="10"></td>
											</tr>
											<tr>
												<td><strong>Total Earnings</strong></td>
												<td><strong>9,999.00</strong></td>
												<td><strong>Total Deduction</strong></td>
												<td><strong>200.00</strong></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td><strong style="color:#219BFF;">NET SALARY</strong></td>
												<td><strong>{{ number_format(round($user['net_salary']),2) }}</strong></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td height="25"></td>
							</tr>
							<tr>
								<td>
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="employeeinfo">
										<tbody>
											<tr>
												<th colspan="4">Payment Information for Bank Deposit/Transfer</th>
											</tr>
											<tr>
												<td width="25%"><strong>Name of Bank:</strong></td>
												<td width="25%">{{ $user['bank_name'] }}</td>
												<td width="25%"><strong>Account #</strong></td>
												<td width="25%">{{ $user['account_number'] }}</td>
											</tr>
											<tr>
												<td><strong>Account Title:</strong></td>
												<td>{{ $user['account_holder'] }}</td>
												<td><strong>Transfer Date:</strong></td>
												<td>23-01-2020</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td height="40"></td>
							</tr>
							<tr>
								<td>
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="employeeinfo">
										<tbody>
											<tr>
												<td width="25%" valign="middle">Employee Signature:</td>
												<td width="25%" valign="middle">&nbsp;</td>
												<td width="25%" valign="middle">HR  Signature:</td>
												<td width="25%" style="padding: 0;"><img src="{{ asset('admin/images/stamp.png') }}" alt="Stamp"></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td height="40"></td>
							</tr>
							<tr>
								<td>(This is a computer generated copy. Signature is not required.)</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
</table>
</body>
</html>