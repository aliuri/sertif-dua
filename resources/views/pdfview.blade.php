
<style type="text/css">
	table td, table th{
		border:1px solid black;
	}
</style>
<div class="container">


	<br/>
	<a href="{{ route('pdfview',['download'=>'pdf']) }}">Download PDF</a>


	<table>
		<tr>
			<th>No</th>
			<th>Title</th>
			<th>Description</th>
		</tr>
		@foreach ($items as $key => $item)
		<tr>
			<td>{{ ++$key }}</td>
			<td>{{ $item->name }}</td>
			<td>{{ $item->email }}</td>
			<td>{{ $item->partisipan }}</td>
			<td>{{ $item->id_event }}</td>
		</tr>
		@endforeach
	</table>
</div>