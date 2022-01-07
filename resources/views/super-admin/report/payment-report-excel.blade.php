 <table class="distict_table">
    <thead>
        <tr class="tab">
            <th>S.NO </th>    
            <th>NAME</th>
            <th>CONTACT NUMBER</th> 
            <th>INVOICE ID</th> 
            <th>DATE</th> 
            <th>NEXT RENEWAL DATE</th>   
            <th>PLAN</th>  
            <th>COUPON CODE</th>  
            <th>STATUS</th>
            <th>PAID BY</th>  
            <th>AMOUNT(CAD)</th>
        </tr>
    </thead>
    <tbody>
        @if(count($payments) > 0)
        @foreach($payments as $key=>$payment)
        <tr>
            <td>{{++$key}}</td>
            <td>{{ucfirst($payment['user']['name'])}}</td>
            <td>{{$payment['user']['userDetail']['contact_no']}}</td>
            <td>#KH{{$payment->plan_id}}{{$key+1}}u{{ $payment->user_id}}</td>
            <td>{{date('m/d/Y',strtotime($payment->created_at))}}</td>
            <td>{{date('m/d/Y',strtotime($payment->renewal_date))}}</td>
            <td style="text-transform: capitalize;">{{ optional($payment->subscription)->plan_name }}</td>
            <td>@if($payment->coupon_id !='0')
              {{ optional($payment->coupon)->coupon_code }}
               @else
               - 
               @endif
           </td>
           <td>@if($payment->status=='1')
              Success
              @elseif($payment->status=='2')
              Faild  
              @endif 
          </td>  
          <td>  
            @if($payment->payment_type=='1')
            Cash
            @elseif($payment->payment_type=='2')
            Cheque
            @elseif($payment->payment_type=='3')
            Online Payment
            @elseif($payment->payment_type=='0')
              @if($payment['user']['user_role_id'] == 2)
                  -
              @else
                  Offline Payment
              @endif 
            @endif 
        </td> 
        <td>@if($payment->amount !='0.00')
          CAD {{$payment->amount}}
          @else
          - 
          @endif
      </td> 
  </tr>
  @endforeach
  @else
  <tr> 
    <td align="center" colspan="11">No Users Payment Reports Available</td>
</tr>
@endif

</tbody>
</table>    