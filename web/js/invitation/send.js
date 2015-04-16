$("#selectUsersForm").on('submit', function () {
    $("#selectUsersForm").find('input, select').attr('disabled', false);
});

$("#addUserToInvitation").click(function () {
    moveFromSelectToAnother($("#allUsers"), $("#selectedUsers"), true);
});
$("#delUserFromInvitation").click(function () {
    moveFromSelectToAnother($("#selectedUsers"), $("#allUsers"));
});

function moveFromSelectToAnother(sourceJElement, destinationJElement, markAsSelect) {
    var selectedUsers = sourceJElement.find("option:selected");

    for (var i = 0; i < selectedUsers.length; i++) {
        var attributes = {
            value: $(selectedUsers[i]).val(),
            text: $(selectedUsers[i]).text()
        };
        if (markAsSelect) {
            attributes.selected = "selected";
        }
        destinationJElement.append($('<option>', attributes));
    }
    selectedUsers.remove();
}