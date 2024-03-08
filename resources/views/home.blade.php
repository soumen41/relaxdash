@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>
    
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
    
                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <br/>--}}
        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="row">
                <div class="col-lg-6">
                <div class="col-auto">
                    <div class="form-group">
                        <input type="file" name="file" accept=".csv" class="form-control">
                    </div>
                </div>
                </div>
                <div class="col-lg-6">
                <button type="submit" class="btn btn-primary">Import CSV</button>
                </div>
                </div>
            </div>
        </form>
        <br/>
        <table class="table table-bordered">
            <thead>
              <tr>
                <td><input type="checkbox" name="master" id="master"></td>
                <th>ID</th>
                <th>Order ID</th>
                <th>Created At</th>
              </tr>
            </thead>
            <tbody>
                @forelse ($getData as $key => $row)
                <tr>
                    <td><input type="checkbox" name="child" id="child" class="child" data-id="{{ $row['order_id'] }}"></td>
                    <td>{{ ++$key }}</td>
                    <td>{{ $row['order_id'] }}</td>
                    <td>{{ $row->created_at }}</td>
                  </tr>      
                @empty
                <tr>
                   <td colspan="3">No data found</td>
                </tr>
                @endforelse
            </tbody>
          </table>
          <div class="form-check-inline">
            <label class="form-check-label">
              <input type="radio" class="form-check-input" name="optradio" value="1" id="fraud">Fraud
            </label>
          </div>
          <div class="form-check-inline">
            <label class="form-check-label">
              <input type="radio" class="form-check-input" name="optradio" value="2" id="blacklist">Blacklist
            </label>
          </div>
          <div class="form-check-inline disabled">
            <label class="form-check-label">
              <input type="radio" class="form-check-input" name="optradio" value="3" id="refund">Refund
            </label>
          </div>
          <button type="button" class="btn btn-primary" id="apply">Apply</button>
    </div>
@push('js_src')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#master').on('click', function(e) {  
         if($(this).is(':checked',true))    
         { 
            $(".child").prop('checked', true);    
         } else {    
            $(".child").prop('checked',false);    
         }    
    });
});
$("#apply").on("click", function(e){
    e.preventDefault()
    if($('input[name="optradio"]').is(':checked')){
        var opvalue = $("input[name='optradio']:checked").val();
        sendValue(opvalue)
    }else{
        alert("Select atleast radio.");
    }

    function sendValue(param){
        var allVals = [];
        $(".child:checked").each(function() {    
            allVals.push($(this).attr('data-id'));
        });
        if(allVals.length <=0)
        {    
          alert("Please select checkbox.");   
        }else{
            var join_selected_values = allVals.join(",");
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "post",
                url: "{{ route('getData') }}",
                data: {
                'ids':join_selected_values,
                "param": param
                },
                dataType: "json",
                success: function (response) {
                    console.log(response)
                }
            });
        }
    }        
})
</script>    
@endpush
@endsection
