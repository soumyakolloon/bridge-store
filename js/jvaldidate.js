function requireValidation(id)
{
if(id=='')
return false;
else
return true;
}

function ValidateEmail(mail) 
{
 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(myForm.emailAddr.value))
  {
    return (true)
  }
    alert("You have entered an invalid email address!")
    return (false)
}
function pvalidate(p1, p2) {
if (p1 != p2)
{
alert('Passwords did not match!');
return true;
}
else
{
return true;
}
}