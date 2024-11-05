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
  <title>Sign In</title>

</head>

<body>

  <section class="login-container">
    <div class="container ">
      <div class="row">
        <div class="col-lg-6"></div>
        <div class="col-lg-6 form_section">
          <div class="login-form ">
            <div class="row">
              <div class="col-6">
                <h2 class="text-left set_mainheading">Sign In</h2>

              </div>
              <div class="col-6">
                <a class="text-left setacount" href="{{url('/signup')}}">Create Your Acount</a>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6">

                <button class="btn setBtn_primary mb-2"><i class="fa-brands fa-google" style="color: #ffffff;"></i> Sign
                  in with Google</button>
              </div>
              <div class="col-lg-6 set_facebook">

                <button class="btn setBtn_primary_white mb-2"> <i class="fa-brands fa-facebook-f"
                    style="color: #005b96;"></i> With Facebook</button>
              </div>
            </div>
            <div class="Set_usign">Or Sign in Using Your Email Address</div>
            @if (session('error'))
            <div class="col-sm-12">
              <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            @endif
            <form action="{{route('login')}}" method="post">
              @csrf()
              <div class="row set-data">
                <div class="form-group col-lg-6  ">
                  <label for="email">Your Email</label>
                  <input type="email" class="form-control" name="email" id="email" placeholder="typeyourmail@example.com">
                  @if ($errors->has('email'))
                  <div style="color: red;">
                    {{ $errors->first('email') }}
                  </div>
                  @endif
                </div>

                <div class="form-group col-lg-6 set-pass-icon">
                  <label for="password">Password</label>
                  <input id="password-field" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                  @error('password')
                  <div style="color:red">{{ $message }}</div>
                  @enderror
                  <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                </div>
                <!-- <div class="form-check col-lg-4 set_remember">
                  <input type="radio" class="form-check-input" id="rememberMe">
                  <label class="form-check-label" for="rememberMe">Remember Me</label>
                </div> -->
                <div class="col-lg-8 set_pass ">
                  <a href="{{ route('forgetpassword') }}">Forgot Password?</a>
                </div>
                <div class=" col-12 set_sign">
                  <button type="submit" class="setBtn_primary signin_button">Sign In</button>
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
    $(".toggle-password").click(function() {

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