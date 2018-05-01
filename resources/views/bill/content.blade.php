
<div class="container">
    <div class="row">
        <div class="col-md-7">
            Quản Lý Hoá Đơn
        </div>
        @include('bill.search')
    </div>
    <table class="table" style="table-layout: fixed; ">
        <thead>
        <tr>
            <th  style="vertical-align: middle; width: 50px; ">STT</th>
            <th style="vertical-align: middle; width: 100px"><a class="ajaxlink" href="javascript:ajaxLoad('{{ url('bills?field=bills_code&sort='.(request()->session()->get('sort')=='asc'?'desc':'asc')) }}')">Bill Code</a>
                {{ request()->session()->get('field')=='bill_code'?(request()->session()->get('sort')=='asc'?'▴':'▾'):'' }}
            </th>
            <th style="vertical-align: middle; width: 150px"><a class="ajaxlink" href="javascript:ajaxLoad('{{ url('bills?field=employee_code&sort='.(request()->session()->get('sort')=='asc'?'desc':'asc')) }}')">Employee Code</a>
                {{ request()->session()->get('field')=='employee_code'?(request()->session()->get('sort')=='asc'?'▴':'▾'):'' }}
            </th>
            <th style="vertical-align: middle; width: 100px"><a class="ajaxlink" href="javascript:ajaxLoad('{{ url('bills?field=total&sort='.(request()->session()->get('sort')=='asc'?'desc':'asc')) }}')">Total</a>
                {{ request()->session()->get('field')=='total'?(request()->session()->get('sort')=='asc'?'▴':'▾'):'' }}
            </th>
            <th style="vertical-align: middle; width: 100px"><a class="ajaxlink" href="javascript:ajaxLoad('{{ url('bills?field=was_paid&sort='.(request()->session()->get('sort')=='asc'?'desc':'asc')) }}')">Was Paid</a>
                {{ request()->session()->get('field')=='was_paid'?(request()->session()->get('sort')=='asc'?'▴':'▾'):'' }}
            </th>
            <th style="vertical-align: middle; width: 100px"><a class="ajaxlink" href="javascript:ajaxLoad('{{ url('bills?field=created_at&sort='.(request()->session()->get('sort')=='asc'?'desc':'asc')) }}')">Created At</a>
                {{ request()->session()->get('field')=='created_at'?(request()->session()->get('sort')=='asc'?'▴':'▾'):'' }}
            </th>
            <th width="160px" style="vertical-align: middle">
                <a href="{{ route('bills.create') }}"
                   class="btn btn-primary btn-xs"> <i class="fa fa-plus" aria-hidden="true"></i> New bills</a>
            </th>
        </tr>
        </thead>
        <tbody>
        @php
            $i = 1 + ($page - 1) * 5;
        @endphp
        @forelse($bills as $bill)
            <tr>
                <td style="overflow: hidden">{{ $i++ }}</td>
                <td style="overflow: hidden">{{ $bill->bill_code }}</td>
                <td style="overflow: hidden">{{ $bill->employee_code }}</td>
                <td style="overflow: hidden">{{ $bill->total }}</td>
                <td style="overflow: hidden"> {{ $bill->was_paid == 1 ? "Yes" : "No" }}</td>
                <td style="overflow: hidden"> {{ $bill->created_at }}</td>
                <td>
                    <a class="btn btn-warning btn-xs" title="Edit"
                       href="{{url(route('bills.edit', ['id' => $bill->id]))}}">
                        Edit</a>
                    <input type="hidden" name="_method" value="delete"/>
                    <a class="btn btn-danger btn-xs" title="Delete"
                       href="javascript:if(confirm('Are you sure want to delete?')) ajaxDelete('{{url(route('bills.destroy', ['id' => $bill->id]))}}','{{csrf_token()}}')">
                        Delete
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" style="vertical-align: center; text-align: center">No Data</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <ul class="paginate">
        {{ $bills->links() }}
    </ul>
</div>