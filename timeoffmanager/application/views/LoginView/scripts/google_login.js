
/**
 * Description of onSignIn
 * @package Javascript helper fuction
 * @author Judit Alföldi
 * 
 * The application uses the Google login authentication
 * to register new users. The onSignIn responsible for it.
 * 
 */

function onSignIn(googleUser) {
    var profile = googleUser.getBasicProfile();                       

    var fullName = profile.getName();
    var imageURL = profile.getImageUrl();
    var emailAddress = profile.getEmail();
            
    $.ajax(
    {
        type: "POST",
        url: encodeURI("http://localhost/timeoffmanager/Login/Route"),
        data:{email : emailAddress, name : fullName, url: imageURL},
        cache:false,
        success:function(data)
        {
            window.open(data, "_self");
        }
    }); 
}



