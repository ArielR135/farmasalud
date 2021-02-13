$("#frmAcceso").on('submit',function(e) {
    e.preventDefault();
    nombre_usuario=$("#nombre_usuario").val();
    contraseña=$("#contraseña").val();

    $.post("../ajax/usuario.php?op=verificar",
        {"nombre_usuario":nombre_usuario,"contraseña":contraseña},
        function(data) {
            if (data != "null") {
                $(location).attr("href","escritorio.php");
            } else {
                bootbox.alert("Usuario y/o Contraseña son incorrectos.");
            }
        }
    );
});