$("a").click(function() {
    $("#list").addClass("d-none");
    $("#tabela").removeClass("d-none");
    // console.log($(this).text());
});

$(document).ready(function() {
    $("#inicial").change(function() {
        // console.log($(this).val());
    });
    $("#final").change(function() {
        // console.log($(this).val());
    });
});

let ready = $(document).ready(function () {
    $("#selectAno").change(function () {
        const selectAno = $(this).val();
        //console.log(selectAno);
    });
});

$("#selectAno").each(function() {
    const selectAno = $(this).val();
    // console.log(selectAno);
});

const loadFile = function (event) {
    const output = document.getElementById("preview");
    output.src = URL.createObjectURL(event.target.files[0]);
};

function readURL(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $("#imagePreview").css(
                "background-image",
                "url(" + e.target.result + ")"
            );
            $("#imagePreview").hide();
            $("#imagePreview").fadeIn(650);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});
