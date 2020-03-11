function busca(e){
    $('#form-filtro').append("<input type='hidden' name='mes' id='mes' value='"+e+"'>").submit();

    // $('#form-filtro').submit();
}

function adicionaFerias(value) {
    console.log(String(value));
    const matricula = String(value).padStart(6, '0');

    console.log(matricula);
    let form = $('#modal-form-ferias');

    form.html('');
    form.append(
        "<div class='form-group'>"+
            "<input type='hidden' name='matricula' value='"+ matricula +"'>"+
            "<label for='inicio'>data do ínicio das férias:</label>"+
            "<input type='date' class='form-control' name='data_inicial' id='inicio'>"+
        "</div>"+
        "<div class='form-group'>"+
            "<label for='fim'>data do fim das férias:</label>"+
            "<input type='date' class='form-control' name='data_final' id='fim'>"+
        "</div>"+
        "<div class='form-group'>"+
            "<label for='observacao'>data do fim das férias:</label>"+
            "<textarea class='form-control' name='observacao' id='observacao'></textarea>"+
        "</div>"+
        "<button type='submit' class='btn btn-primary'>confirmar</button>"
    );
}