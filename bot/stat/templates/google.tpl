<h2>Google Summary</h2>

<table class='table table-striped table-bordered'>
	<thead>
	<tr>
		<th>Signups</th>
		<th>Topups</th>
		<th>Topup Attempts</th>
		<th>Topup Count</th>
		<th>Topup Attempt Count</th>
		<th>Once</th>
		<th>Twice</th>
		<th>Three</th>
		<th>Three+</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td>{$rows.signup}</td>
		<td>{$rows.payment}</td>
		<td>{$rows.payment_attempt}</td>
		<td>{$rows.paycount}</td>
		<td>{$rows.payattemptcount}</td>
		<td>{$rows.first}</td>
		<td>{$rows.second}</td>
		<td>{$rows.third}</td>
		<td>{$rows.other}</td>
	</tr>
	</tbody>
</table>