 <table class="distict_table">
    <thead>
        <tr class="tab">
            <th>S.NO </th> 
            <th>FULL NAME</th>
            <th>EMAIL ADDRESS</th>
            <th>CONTACT NUMBER</th>
            <th>ORGANIZATION NAME</th>
            <th> LOGIN DATE</th> 
            <th> RENEWAL DATE</th> 
        </tr>
    </thead>
    <tbody>
        @if(count($users) > 0)
        @foreach($users as $key=>$user)
            <tr>
                <td>{{++$key}}</td>  
                <td>{{ucfirst($user->name)}}</td>
                <td>{{$user->email}}</td>
                <td>@if($user['userDetail']['contact_no'] != null)
                    {{$user['userDetail']['contact_no']}}
                    @endif
                </td>
                <td>@if($user['userDetail']['organization_name'] != null)
                    {{ucfirst($user['userDetail']['organization_name'])}}
                    @endif
                </td>
                <td>{{ optional($user->login_at)->format('m/d/Y h:i a') }}    </td> 
                <td>@if($user['userRenewalDatail']['renewal_date'] != null)  
                    {{ date('m/d/Y', strtotime($user['userRenewalDatail']['renewal_date'])) }}
                    @endif
                </td>

            </tr>
       @endforeach
       @else
             <tr> 
                  <td align="center" colspan="9">No Login Users Available</td>
              </tr>
       @endif
    </tbody>
</table>     