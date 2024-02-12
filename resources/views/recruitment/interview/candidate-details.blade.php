<style>
	tr td{
		border:0px !important;
		font-size: 16px;
		font-weight: bold;
	}
	.btn-success{
		display: none;
	}
</style>

<div class="row">
	<div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
		<table class="table table-responsive">
			<tbody>
				<tr>
					<td width="35%">Full Name</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->full_name }}</td>
				</tr>
				<tr>
					<td width="35%">Fatther Name</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->father_name }}</td>
				</tr>
				<tr>
					<td width="35%">Date Of Birth(DOB) </td>
					<td width="5%">:</td>
					<td width="60%">{{ Carbon\Carbon::parse($cvDetails->date_of_birth)->format('d-m-Y') }}</td>
				</tr>
				<tr>
					<td width="35%">Age</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->age }}</td>
				</tr>
				<tr>
					<td width="35%">Education</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->education }}</td>
				</tr>
				<tr>
					<td width="35%">Passport No.</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->passport_no }}</td>
				</tr>
				<tr>
					<td width="35%">PP Expired Date</td>
					<td width="5%">:</td>
					<td width="60%">{{ Carbon\Carbon::parse($cvDetails->passport_expired_date)->format('d-m-Y') }}</td>
				</tr>
				<tr>
					<td width="35%">Contact No.</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->contact_no }}</td>
				</tr>
				<tr>
					<td width="35%">Reference</td>
					<td width="5%">:</td>
					<td width="60%">{{ @Helper::reference($cvDetails->reference_id)->reference_name }}</td>
				</tr>
				<tr>
					<td width="35%">Country</td>
					<td width="5%">:</td>
					<td width="60%">{{ @Helper::country($cvDetails->country_id) }}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
		<table class="table table-responsive">
			<tbody>
				<tr>
					<td width="35%">Trade Applied</td>
					<td width="5%">:</td>
					<td width="60%">{{ @Helper::singleTrade($cvDetails->trade_applied)->trade_name }}</td>
				</tr>
				<tr>
					<td width="35%">Selected Trade</td>
					<td width="5%">:</td>
					<td width="60%">{{ @Helper::singleTrade($cvDetails->selected_trade)->trade_name  }}</td>
				</tr>
				<tr>
					<td width="35%">Interview Attend</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->interview_attend == 1?'Yes':'No' }}</td>
				</tr>
				<tr>
					<td width="35%">Interview Selected Status</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->interview_selected_status == 1?'Accepted':($cvDetails->interview_selected_status == 2?'Waiting':($cvDetails->interview_selected_status == 3?'Decline':'')) }}</td>
				</tr>
				<tr>
					<td width="35%">Selection Date</td>
					<td width="5%">:</td>
					<td width="60%">{{ Carbon\Carbon::parse($cvDetails->selection_date)->format('d-m-Y') }}</td>
				</tr>
				<tr>
					<td width="35%">Select Passport Status</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->passport_status == 1?'In Office':($cvDetails->passport_status == 2?'Yes':($cvDetails->passport_status == 3?'No':'')) }}</td>
				</tr>
				<tr>
					<td width="35%">Home Experience</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->home_experience }}</td>
				</tr>
				<tr>
					<td width="35%">Oversease Experience</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->oversease_experience }}</td>
				</tr>
				<tr>
					<td width="35%">Salary A/D</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->salary_ad == 1?'Accepted':($cvDetails->salary_ad == 2?'Not Accepted':'') }}</td>
				</tr>
				<tr>
					<td width="35%">Salary & Others</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->salary_and_others }}</td>
				</tr>
				<tr>
					<td width="35%">Grade</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->grade }}</td>
				</tr>
				<tr>
					<td width="35%">Remarks</td>
					<td width="5%">:</td>
					<td width="60%">{{ $cvDetails->remarks }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>