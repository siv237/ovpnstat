Notification.requestPermission( newMessage );

function newMessage(permission) {
    if( permission != "granted" ) return false;
    var notify = new Notification("Thanks for letting notify you");
};
var mailNotification = new Notification("Андрей Чернышёв", {
    tag : "ache-mail",
    body : "Привет, высылаю материалы по проекту...",
    icon : "http://habrastorage.org/storage2/cf9/50b/e87/cf950be87f8c34e63c07217a009f1d17.jpg"
});
