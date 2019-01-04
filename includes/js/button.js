// Change the source of an image
function imageSwap(ImageName, NewSource)
{
  var objStr,obj;
  if(document.images)
  {
    if (typeof ImageName == 'string')
    {
      objStr = 'document.' + ImageName;
      obj = eval(objStr);
      obj.src = NewSource;
    }
    else if ((typeof ImageName == 'object') && ImageName && ImageName.src)
    {
      ImageName.src = NewSource;
    }
  }
}

function submitForm(pressButton){
  var form = document.adminForm;
    form.op.value = pressButton;
      form.submit();
}
