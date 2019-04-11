<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <title>Sll Checker</title>
  </head>
  <body>
    <div class="container" style="margin-top: 30px">
     
            @if ($errors->any())
              <div class="row">
                <div class="col-sm-12 text-center">
                  <div class="card">
                    <div class="card-body">
                      <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endif
            @if(session()->has('message.level'))
             
            <div class="row">
              <div class="col-sm-12 text-center">
                <div class="card">
                  <div class="card-body">
                      <div class="alert alert-{{ session('message.level') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                             <div class="alert alert-{{ session('message.level') }}">
                              <strong>Success!</strong> {{ session('message.content') }}
                            </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>

             @endif
       
      <div class="row">
        <div class="col-sm-1">&nbsp;</div>
        <div class="col-sm-10">
           <div class="card">
             <div class="card-header text-right">
              <span class="text-center text-secondary"> Domain Details</span>
             
             <button type="button" class="btn btn-info btn-sm " data-toggle="modal" data-target="#myModal"><i class="fa fa-plus" aria-hidden="true"></i></button>
             </div>
              <div class="card-body">
                <table class="table table-sm">
                  <thead>
                    <th>Sl#</th>
                    <th>Domain Name</th>
                    <th>SSL Expiry Date</th>
                    <th>SSL Issuer</th>
                    <th>Action</th>
                  </thead>
                  <tbody>
                      @foreach($sllChecker as $key => $value)
                      <tr id="removeAfterDelete__{{ $value['id'] }}">
                        <td>{{ (($sllChecker->currentPage() - 1 ) * $sllChecker->perPage() ) + $loop->iteration }}</td>
                        <td>{{ $value->domain_name }}</td>
                        <td id="ssl_expiry__{{  $value->id }}">{{ ($value->ssl_expiry!=null)?$value->ssl_expiry:'unchecked' }}</td>
                        <td id="ssl_issuer__{{  $value->id }}">{{ ($value->ssl_issuer!=NULL)?$value->ssl_issuer:'unchecked' }}</td>
                        <td style="white-space: nowrap">
                          <button  id="{{ $value['id'] }}"  class="btn btn-success refresh  btn-sm"><i class="fa fa-undo" aria-hidden="true"></i></button> 
                          <button id="{{ $value['id'] }}" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i></button> 
                  
                  </td>
                      </tr>
                      @endforeach
                  </tbody>
                </table>
              </div>
               <div class="card-footer">
                 {{ $sllChecker->links() }}
               </div>
          </div>
        </div>
        <div class="col-sm-1">&nbsp;</div>
      </div>
    </div>
    <!-- Trigger the modal with a button -->


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Domain</h4>
      </div>
      <div class="modal-body">
              {{ Form::open(array('url' => '/sslchecker')) }}
               {{ csrf_field() }}
                    <div class="form-group">
                      <label for="domain_name">Domain Name:</label>
                       {{Form::text('domain_name',isset($domain_name)?$domain_name: '', ['class' => 'form-control', 'placeholder' => 'Please Enter Your Domain','maxlength'=>'100'] )}}
                    </div>
                    <div class="text-center">
                       <button type="submit" class="btn btn-primary" >Submit</button>
                    </div>
              {{ Form::close() }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
      $(document).ready(function()
      {
        $(document).on('click', '.refresh', function(){  
          var id = $(this).attr('id');
          $('#ssl_expiry__'+id).html('<i class="fa fa-refresh fa-spin" aria-hidden="true"></i>');
          $('#ssl_issuer__'+id).html('<i class="fa fa-refresh fa-spin" aria-hidden="true"></i>');
          $.ajax({
              type:"POST",
              url: "{{  url('/') }}/sslchecker/refresh",
              data:{
                    "_token": "{{ csrf_token() }}",
                    id : id
              },
              dataType : 'html',
              cache: false,
              success: function(data){
                  domainUpdatedData=JSON.parse(data);
                  if(domainUpdatedData!='error')
                  {
                    $('#ssl_expiry__'+id).html(domainUpdatedData.ssl_expiry);
                    $('#ssl_issuer__'+id).html(domainUpdatedData.ssl_issuer);
                  }
                  else
                  {
                    console.log("something went wrong");
                  }
                  
              },
              error: function(error)
              {
                  $.ajax({
                      type:"POST",
                      url: "{{  url('/') }}/sslchecker/refreshAfterFail",
                      data:{
                            "_token": "{{ csrf_token() }}",
                            id : id
                      },
                      dataType : 'html',
                      cache: false,
                      success: function(data){
                          domainUpdatedData=JSON.parse(data);
                          if(domainUpdatedData!='error')
                          {
                            $('#ssl_expiry__'+id).html(domainUpdatedData.ssl_expiry);
                            $('#ssl_issuer__'+id).html(domainUpdatedData.ssl_issuer);
                          }
                          else
                          {
                            console.log("something went wrong");
                          }
                          
                      },
                      error:function()
                      {
                        console.log("something went wrong");
                      }
                    });
                 

              }
            });
          });
        $(document).on('click', '.delete', function(){ 
               var id = $(this).attr('id');
                swal({
                  title: "Are you sure?",
                  text: "You will data will deleted!",
                  icon: "warning",
                  buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                  ],
                  dangerMode: true,
                }).then(function(isConfirm) {
                  if (isConfirm) {
                                    $.ajax({
                                    type:"POST",
                                    url: "{{  url('/') }}/sslchecker/delete",
                                    data:{
                                          "_token": "{{ csrf_token() }}",
                                          id : id
                                    },
                                    dataType : 'html',
                                    cache: false,
                                    success: function(data){
                                        $('#removeAfterDelete__'+id).remove();  
                                        
                                    },
                                    error:function()
                                    {
                                      console.log("something went wrong");
                                    }
                                    });

                  } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                  }
                })

            });
      });
    </script>
  </body>
</html>