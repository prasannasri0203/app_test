<table class="distict_table">
  <thead>
    <tr class="tab"> 
      <th>S.No </th>
      <th>FULL NAME</th>
      <th>EMAIL ADDRESS</th>
      <th>CONTACT NUMBER</th> 
      <th>LOGIN DATE</th>  
      <th>STATUS</th>

    </tr>
  </thead>
  <tbody>
    @if(count($users) > 0)
    @foreach($users as $key=>$user)
    <tr>
        <td>{{++$key}}</td>  
        <td> {{ucfirst($user->name)}} </td>
        <td> {{$user->email}} </td>
        <td> {{$user->userDetail->contact_no}} </td> 
        <td>{{ optional($user->login_at)->format('m/d/Y h:i a') }} 
        </td>  
        <td> @if($user->status ==1) Active @else Inactive @endif </td>
      </tr>   
      @endforeach

      @else
      <tr> 
        <td class="text_cen" colspan="9">No Login Users Available</td>
      </tr>
      @endif
    </tbody>
</table>