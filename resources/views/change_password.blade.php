
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
    rel="stylesheet">
  <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/sign.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Reset Password</title>

</head>
<style>

.login-form {
    box-shadow: 0px 1px 17.1px 0px #96969645;
    min-height: 450px;
    display: flex;
    border-radius: 10px;
    padding: 50px 80px;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
  .set_facebook h3{
   color: #005B96 !important;
  }.set-data {
    display: flex;
    justify-content: center;
}.set_facebook  h6{
  
  color: #434343;
    font-size: 18px;
    line-height:25px;
    font-family: roboto;
    font-weight: 600;
}
  .verify_button {   
    width: 60%;
    margin: auto;
    display: block;
    background: linear-gradient(88deg, #004485 -1%, #00315f 57%);
    color: #fff;
    font-size: 20px;
    font-weight: 500;
    padding: 10px 20px;
    font-family: 'Roboto';
    box-shadow: -1px 1px 9px 1px #8E8E8E;
    border: none;
    border-radius: 5px;
    letter-spacing: 0.5px;
}</style>
<body>

  <section class="login-container">
    <div class="container ">
      <div class="row">
        <div class="col-lg-6"></div>
        <div class="col-lg-6 form_section">
          <div class="login-form  set_facebook">
           
            <h3 class="text-center">Change Password</h3>
            <form action ="{{route('saveChangePassword')}}" method ="post">
              @csrf
              <div class="row set-data">
                 <div class="form-group col-lg-12  mt-3 ">
                  <input type="password" class="form-control mt-3 @error('password') is-invalid @enderror" name="password" id="email" placeholder="Enter Your password" >
                 @error('password')
                      <div style="color:red">{{ $message }}</div>
                  @enderror
                </div>
                 <div class="form-group col-lg-12  mt-3 ">
                  <input type="password" class="form-control mt-3 @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="email" placeholder="Enter Confirm Password" >
                   @error('password_confirmation')
                      <div style="color:red">{{ $message }}</div>
                  @enderror
                </div>
                 
                <div class=" col-12 set_sign mt-3">
                <button type="submit" class="verify_button">Send</button>
              </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    $(".toggle-password").click(function () {

      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
  </script>
</body>

</html>