<html lang="en">

<head>
  <meta name="google-signin-scope" content="profile email">
  <meta name="google-signin-client_id" content="847988252764-9e1mcnedo52037n0l0scf3hdo2bpgnmv.apps.googleusercontent.com">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:100,100i,400">
  <style>
    body {
      background-color: #37474f;
    }
    
    h1 {
      color: white;
      font-family: "Roboto", sans-serif;
      font-weight: 500;
    }
    
    h3 {
      color: white;
      font-family: "Roboto", sans-serif;
      font-weight: 100;
    }
    
    .center {
      margin-top: 10%;
      text-align: center;
    }
    
    .g-signin2 {
      margin-top: 5%;
      width: 100%;
    }
    
    .g-signin2 > div {
      margin: 0 auto;
    }
  </style>
</head>

<body>
  <div class='center'>
    <img src="https://www.nssc.org/images/design/register-icon.png" alt="Logo" style="max-width:100px; max-height:100px;">
    <h1>Receipt Database</h1>
    <h3>A way to catalog and analyze email receipts.</h3>
    <div class="g-signin2" data-onsuccess="onSignIn" data-theme="light" data-width="300" data-height="50" data-longtitle="true"></div>
    <!--<a href="#" onclick="signOut();">Sign out</a>-->
    <div>
      <script>
      window.onbeforeunload = function(e){
        signOut();

      }
        function onSignIn(googleUser) {
          // Useful data for your client-side scripts:
          var profile = googleUser.getBasicProfile();
          console.log("ID: " + profile.getId()); // Don't send this directly to your server!
          console.log('Full Name: ' + profile.getName());
          console.log('Given Name: ' + profile.getGivenName());
          console.log('Family Name: ' + profile.getFamilyName());
          console.log("Image URL: " + profile.getImageUrl());
          console.log("Email: " + profile.getEmail());
          // The ID token you need to pass to your backend:
          var id_token = googleUser.getAuthResponse().id_token;
          $.ajax({
            type: 'POST',
            data: {
              id_token: id_token
            },
            url: '../php/login_process.php',
            success: function(data) {
              window.location.href = 'index.php';
            }

          });
          // console.log("ID Token: " + id_token);
        }

        function signOut() {
          var auth2 = gapi.auth2.getAuthInstance();
          auth2.signOut().then(function() {
            console.log('User signed out.');
          });
        }
      </script>
</body>

</html>