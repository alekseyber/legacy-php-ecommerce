$(document).ready(function () {
    setDivelTeahead();
    $(document).on('click', '.btn-default-img-pr', setDefaultImg);
    $(document).on('change', '.img-sortvalue', setSortvalueImg);
    $(document).on("submit", "form.js-form-edit-modif", editModification);
    $(document).on("submit", "form.js-form-new-modif", editModification);
    $(document).on('click', '.del-item', delImg);
    $(document).on('click', '.del-modif', delModif);
    $(document).on("submit", "form.js-form-edit-product", editModification);
    //  $('.js-input-form').focusout(changeInput);
//	$(document).on('focusout', '.js-input-form', changeInput);
    $(document).on('input', '.js-input-form', throttle(changeInput));
    $(document).on('change', '.js-input-form-change', changeInput);
    $("#dopcategory").select2ToTree();
    $(".formprcessor.select.multiple").select2({
        theme: "bootstrap"
    });
    $("input[name='phone']").inputmask("79999999999");
    $(".ip-adress").inputmask("9{1,3}.9{1,3}.9{1,3}.9{1,3}");
    //$('#datepicker').inputmask('yyyy-mm-dd', {'placeholder': 'yyyy-mm-dd' });
    $(document).on('change', '.order-status-select', changeStatus);
    $(document).on('change', '.order-contact-copy', statusFilter);
    $(document).on('click', '.delete-cache', deleteCache);
    $(document).on('change', '.order-status-filter', statusFilter);
    $(document).on("submit", "form.js-form-edit-order", editOrder);
    $(document).on('click', '#copy-button', function () {
        $('#text-aria-kurier').select();
        return false;
    });
    $(document).on("submit", "form.js-form-edit-lastmodified", editLastmodified);
    $('.fileupload').each(function () {
        $(this).fileupload({
            dropZone: $(this),
            dataType: 'json',
            singleFileUploads: false,
            add: function (e, data) {
                $(this).closest('.box').find('.overlay').css({'display': 'block'});
                console.log(data);
                data.submit();
            },
            done: function (e, data) {
                var divIdPr = '#multi';
                if (data.result[0].divIdPr) {
                    divIdPr = data.divIdPr;
                }
                var div = $(divIdPr + $(this).data('id'));
                // console.log(data.result);
                $.each(data.result, function (index, file) {
                    var divIdPrItem = '#multi';
                    if (file.divIdPr) {
                        divIdPrItem = file.divIdPr;
                    }
                    if (file.multi) {
                        content = addMulti(file);
                        $(divIdPrItem + file.id).append(content);
                    } else {
                        content = addSingle(file);
                        $(divIdPrItem + file.id).html(content);
                    }

                });
                $(this).closest('.box').find('.overlay').css({'display': 'none'});
                //addMessege(data.result.title, data.result.message, data.result.succes);
                addMessege('Изображения добавлены', 'Изображения в количестве ' + data.result.length + ' - успешно добавлены');
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress' + $(this).data('id') + ' .bar').css(
                        'width',
                        progress + '%'
                        );
            }
        });
    });
    $('#datepicker').datepicker({language: 'ru', clearBtn: true}).on('change', function (e) {
        var $this = $(this),
                action = $this.data('action'),
                value = $this.val();
        window.location = action + value;
        //console.log(value);
    });
    $('.datepickerinput').datepicker({
        language: 'ru'
    });
    $("#newcartproduct").select2({
        placeholder: "Начните вводить наименование товара",
        //minimumInputLength: 2,
        cache: true,
        ajax: {
            url: adminpath + "/order/new-product",
            delay: 250,
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                //console.log(data);
                return {
                    results: data.items
                };
            }
        }
    });

    if ($("body *").is('#editor1')) {
        CKEDITOR.replace('editor1', {
            language: 'ru',
            uiColor: '#9AB8F3',
            height: '600px',
            baseHref: '/'
        });
        //$('#editor1').ckeditor();
    }
    if ($("body *").is('#editor2')) {
        CKEDITOR.replace('editor2', {
            language: 'ru',
            uiColor: '#9AB8F3',
            height: '300px',
            baseHref: '/'
        });
        //$('#editor1').ckeditor();
    }

    if ($("body *").is('.editorfield')) {

        CKEDITOR.replaceAll('editorfield', {
            language: 'ru',
            uiColor: '#9AB8F3',
            height: '300px',
            baseHref: '/'
        });
    }

    $(document).on('click', '.edit-seolink', editSeolink);
});

function editSeolink() {
    var input = $("#input-seolink");
    var table = input.data('table');
    var seolink = input.data('seolink');
    var action = input.data('action');
    var newseolink = input.val();
    //console.log(newseolink);
    $.ajax({
        url: action,
        data: {table, seolink, newseolink},
        type: 'POST',
        dataType: "json",
        success: function (res) {
            addMessege(res.title, res.message, res.succes);
            input.attr('value', res.newseolink);
            input.attr('data-seolink', value);
            // console.log(res.newseolink)
        },
        error: function (e) {
            console.log(e);
        }
    });
    return false;
}
$('.delete').click(function () {
    var res = confirm('Подтвердите действие');
    if (!res)
        return false;
});
function throttle(callback, limit = 2100) {

    var wait = false;
    return (data) => {
        if (!wait) {
            wait = true;
            setTimeout(() => {
                wait = false;
                callback.call(this, data);
            }, limit);
        }
    };
}
function changeInput(event, complete) {

    var $target = $(event.target);
    var id = $target.attr('data-id');    
    var action = $target.attr('data-action');
    var table = $target.attr('data-table');
    var value = $target.val();
    var name = $target.attr('name');
    var chekbox = $target.attr('data-chekbox');
    var checked = $target[0].checked;

    if (chekbox && !checked) {
        var nullval = $target.attr('data-nullval');
        value = nullval ? nullval : false;
    }

    $.ajax({
        url: action,
        data: {id: id, value: value, name: name, table: table},
        type: 'POST',
        dataType: "json",
        success: function (res) {
            addMessege(res.title, res.message, res.succes);
            if (complete) {
                complete(res)
            }
        },
        error: function (e) {
            console.log(e);
        }
    });
//    if (!chekbox) {
//        $target.attr('data-oldval', value);
//        //   $this.attr('value', value);
//    }
}
function statusFilter(e) {
    var $this = $(this),
            action = $this.data('action'),
            value = $this.val();
    value = Number(value);
    window.location = action + value;
}

function changeStatus(event) {
    var $target = $(event.target);
    var id = $target.data('id');
    var action = $target.data('action');
    var value = $target.val();

    $.ajax({
        url: action,
        data: {id: id, value: value},
        type: 'POST',
        dataType: "json",
        success: function (res) {
            //console.log(res);
            addMessege(res.title, res.message, res.succes);
            if (res.succes === true) {
                //  $target.attr('data-oldval', value);
                //  $target.attr('value', value);
                $target.closest('.order-status-select-wrap').find('.order-status-input').attr('value', value);
                if (res.class) {
                    $target.closest('.order-status-bgr').addClass('success');
                }
                if (!res.class) {
                    $target.closest('.order-status-bgr').removeClass('success');
                }
            }

        },
        error: function (e) {
            console.log(e);
        }
    });
}
//delete-cache
function deleteCache(e) {
    var res = confirm('Подтвердите действие');
    if (!res)
        return false;
    var $this = $(this),
            href = $this.attr('href');
    $.ajax({
        url: href,
        dataType: "json",
        success: function (res) {
            addMessege(res.title, res.message, res.succes);
        },
        error: function (e) {
            console.log(e);
        }
    });
    return false;
}
function delImg(e) {
    var res = confirm('Подтвердите действие');
    if (!res)
        return false;
    var $this = $(this),
            id = $this.data('id'),
            src = $this.data('src');
    $.ajax({
        url: adminpath + '/product/delete-gallery',
        data: {id: id, src: src},
        type: 'POST',
        dataType: "json",
        beforeSend: function () {
            $this.closest('.product-img-box').find('.overlay').css({'display': 'block'});
        },
        success: function (res) {
            setTimeout(function () {
                $this.closest('.product-img-box').find('.overlay').css({'display': 'none'});
                if (res.succes === true) {
                    $this.closest('.product-img-box').fadeOut();
                    addMessege(res.title, res.message, res.succes);
                }

            }, 1000);
        },
        error: function () {
            setTimeout(function () {
                $this.closest('.product-img-box').find('.overlay').css({'display': 'none'});
                alert('Ошибка');
            }, 1000);
        }
    });
}

function delModif(e) {
    var res = confirm('Подтвердите действие');
    if (!res)
        return false;
    var $this = $(this),
            id = $this.data('id');
    $.ajax({
        url: adminpath + '/product/delete-modification',
        data: {id: id},
        type: 'POST',
        dataType: "json",
        beforeSend: function () {
            $this.closest('.box').find('.overlay').css({'display': 'block'});
        },
        success: function (res) {
            setTimeout(function () {
                $this.closest('.box').find('.overlay').css({'display': 'none'});
                if (res.succes === true) {
                    $this.closest('#modification' + id).fadeOut();
                    $('#modification' + id + '-tab').closest('li').html('<a class="nav-link">Удалено</a>');
                    //'#modification' + id + '-tab'
                    addMessege(res.title, res.message, res.succes);
                }

            }, 1000);
        },
        error: function (e) {
            console.log(e);
            setTimeout(function () {
                $this.closest('.box').find('.overlay').css({'display': 'none'});
                alert('Ошибка');
            }, 1000);
        }
    });
}

$('.sidebar-menu a').each(function () {
    var location = window.location.protocol + '//' + window.location.host + window.location.pathname;
    var link = this.href;
    if (link == location) {
        $(this).parent().addClass('active');
        $(this).closest('.treeview').addClass('active');
    }
});

$('#reset-filter').click(function () {
    $('#filter input[type=checkbox]').prop('checked', false);
    return false;
});
//select_dopcategory
//$("#dopcategory").select2({
//    placeholder: "Начните вводить наименование категории",
//    //minimumInputLength: 2,
//    cache: true,
//    ajax: {
//        url: adminpath + "/product/related-product",
//        delay: 250,
//        dataType: 'json',
//        data: function (params) {
//            return {
//                q: params.term,
//                page: params.page,
//                category: true
//            };
//        },
//        processResults: function (data, params) {
//
//            return {
//                results: data.items
//            };
//        }
//    }
//});



$("#related").select2({
    placeholder: "Начните вводить наименование товара",
    //minimumInputLength: 2,
    cache: true,
    ajax: {
        url: adminpath + "/product/related-product",
        delay: 250,
        dataType: 'json',
        data: function (params) {
            return {
                q: params.term,
                page: params.page
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        }
    }
});
//if ($('div').is('#single')) {
//    var buttonSingle = $("#single"),
//            buttonMulti = $("#multi"),
//            file;
//}

//if (buttonSingle) {
//    new AjaxUpload(buttonSingle, {
//        action: adminpath + buttonSingle.data('url') + "?upload=1",
//        data: {name: buttonSingle.data('name')},
//        name: buttonSingle.data('name'),
//        onSubmit: function (file, ext) {
//            if (!(ext && /^(jpg|png|jpeg|gif)$/i.test(ext))) {
//                alert('Ошибка! Разрешены только картинки');
//                return false;
//            }
//            buttonSingle.closest('.file-upload').find('.overlay').css({'display': 'block'});
//
//        },
//        onComplete: function (file, response) {
//            setTimeout(function () {
//                buttonSingle.closest('.file-upload').find('.overlay').css({'display': 'none'});
//
//                response = JSON.parse(response);
//                $('.' + buttonSingle.data('name')).html('<img src="/images/product/160/' + response.file + '" style="max-height: 150px;">');
//            }, 1000);
//        }
//    });
//}

//if (buttonMulti) {
//    new AjaxUpload(buttonMulti, {
//        action: adminpath + buttonMulti.data('url') + "?upload=1",
//        data: {name: buttonMulti.data('name')},
//        name: buttonMulti.data('name'),
//        onSubmit: function (file, ext) {
//            if (!(ext && /^(jpg|png|jpeg|gif)$/i.test(ext))) {
//                alert('Ошибка! Разрешены только картинки');
//                return false;
//            }
//            buttonMulti.closest('.file-upload').find('.overlay').css({'display': 'block'});
//
//        },
//        onComplete: function (file, response) {
//            setTimeout(function () {
//                buttonMulti.closest('.file-upload').find('.overlay').css({'display': 'none'});
//
//                response = JSON.parse(response);
//                console.log(response);
//                $('.' + buttonMulti.data('name')).append('<img src="/images/product/160/' + response.file + '" style="max-height: 150px;">');
//            }, 1000);
//        }
//    });
//}
//$("#multiimg").dropzone({ url: path + "/admin/product/add-image" });




$('#add').on('submit', function () {
    if (!isNumeric($('#category_id').val())) {
        alert('Выберите категорию');
        return false;
    }
});
function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

//$(function () {    
//    $('#fileupload').fileupload({
//        dataType: 'json',
//        singleFileUploads: false,
//        formData: {"id": $('#fileupload').data('id')},
//        done: function (e, data) {
//            //console.log(data.result);
//            $.each(data.result, function (index, file) {
//                //console.log(file.name);
//                $('#multi').append('<img src="/images/product/160/' + file.name + '" style="max-height: 150px;">');
//                //$('<p/>').text(file.name).appendTo(document.body);
//            });
//        },
//        progressall: function (e, data) {            
//            var progress = parseInt(data.loaded / data.total * 100, 10);
//            $('#progress .bar').css(
//                    'width',
//                    progress + '%'
//                    );
//        }
//    });
//});



$.ajaxSetup({
    converters: {
        'iframe json': function (iframe) {
            var result = iframe && $.parseJSON($(iframe[0].body).text());
            if (result.error) {
                console.log(result.error);
                // Handling JSON responses with error property:
                // {"error": "Upload failed"}
                throw result.error;
            }
            return result;
        }
    }
});
function addMulti(file) {
    var div = '<div class="col-sm-6 col-md-3 col-lg-2">';
    div = div + '<div class="box box-success product-img-box">';
    div = div + '<div class="box-header with-border">';
    div = div + '<h5 class="box-title"></h5>';
    div = div + '<div class="box-tools pull-left">';
    div = div + '<button type="button" class="btn btn-box-tool btn-default-img-pr " title="Сделать главным фото" data-original-title="Главное превью товара" data-toggle="tooltip" data-placement="top" data-id="' + file.id + '" data-src="' + file.name + '">';
    div = div + '<i class="fa fa-heart"></i></button>';
    div = div + '<button type="button" class="btn btn-box-tool del-item" title="Удалить" data-original-title="Удалить" data-id="' + file.id + '" data-src="' + file.name + '"><i class="fa fa-times"></i></button>';
    div = div + '</div>';
    div = div + '</div>';
    div = div + '<div class="box-body">';
    div = div + '<img src="/images/product/160/' + file.name + '" alt="" class="img-product img-fluid">';
    div = div + '</div>';
    div = div + '<div class="box-footer">';
    div = div + '<p>' + file.name + '</p>';
    div = div + '<select class="form-control img-sortvalue">';
    div = div + '<option value="0" selected="">0</option>';
    div = div + '<option value="1">1</option>';
    div = div + '<option value="2">2</option>';
    div = div + '<option value="3">3</option>';
    div = div + '<option value="4">4</option>';
    div = div + '<option value="5">5</option>';
    div = div + '<option value="6">6</option>';
    div = div + '<option value="7">7</option>';
    div = div + '<option value="8">8</option>';
    div = div + '<option value="9">9</option>';
    div = div + '<option value="10">10</option>';
    div = div + '</select>';
    div = div + '</div>';
    div = div + '<div class="overlay">';
    div = div + '<i class="fa fa-refresh fa-spin"></i>';
    div = div + '</div>';
    div = div + '</div>';
    div = div + '</div>';
    return div;
}

function addSingle(file) {
    var div = '<div class="box box-success product-img-box">';
    div = div + '<div class="box-header with-border">';
    div = div + '<h5 class="box-title"></h5>';
    div = div + '<div class="box-tools pull-left">';
    div = div + '<button type="button" class="btn btn-box-tool del-item-cat-img" title="Удалить" data-original-title="Удалить" data-id="' + file.id + '" data-src="' + file.name + '"><i class="fa fa-times"></i></button>';
    div = div + '</div>';
    div = div + '</div>';
    div = div + '<div class="box-body">';
    div = div + '<img src="' + file.path + file.name + '" alt="" class="img-product img-fluid">';
    div = div + '</div>';
    div = div + '<div class="box-footer">';
    div = div + '<div class="form-group">';
    div = div + '<input type="text" name="img" class="form-control" placeholder="Имя файла" value="' + file.name + '">';
    div = div + '</div>';
    div = div + '</div>';
    div = div + '<div class="overlay">';
    div = div + '<i class="fa fa-refresh fa-spin"></i>';
    div = div + '</div>';
    div = div + '</div>';
    return div;
}

function addMessege(title, p, succes = true) {
    if (succes) {
        div = 'success';
    } else {
        div = 'danger';
    }

    $('#messege-rezult-' + div + ' > h4').html(title);
    $('#messege-rezult-' + div + ' > p').html(p);
    $('#messege-rezult-' + div).delay(400).fadeOut('slow', function () {
        $('#messege-rezult-' + div).css({'display': 'block'});
    });
    $('#messege-rezult-' + div).delay(1900).fadeOut('slow', function () {
        $('#messege-rezult-' + div).css({'display': 'none'});
    });
}


function setDefaultImg(e) {
    var id = Number($(this).data('id')),
            name = $(this).data('src'),
            butt = $(this);
    if (id > 0) {
        $.ajax({
            url: adminpath + '/product/set-defimg',
            data: {id: id, name: name},
            type: 'POST',
            dataType: "json",
            success: function (res) {
                //console.log(res);
                if (res.succes === true) {
                    butt.closest('.row').find('.btn-default-img-pr').removeClass('selected');
                    butt.addClass('selected');
                    addMessege('Главное фото установлено', 'Изображение ' + name + ' - установлено главным');
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    }


}
function setSortvalueImg(e) {
    var id = Number($(this).data('id')),
            name = $(this).data('src'),
            val = $(this).val();
    if (id > 0) {
        $.ajax({
            url: adminpath + '/product/set-sortimg',
            data: {id: id, name: name, val: val},
            type: 'POST',
            dataType: "json",
            success: function (res) {
                //console.log(res);
                if (res.succes === true) {
                    addMessege('Новое значение установлено', 'Значение сортировки ' + val + ' - установлено');
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    }

}


function editModification(e) {
    var formData = $(this).serialize(),
            action = $(this).attr('action'),
            form = $(this);
    if (formData) {
        $.ajax({
            url: action,
            data: formData,
            type: 'POST',
            dataType: "json",
            success: function (res) {
                //console.log(res);
                addMessege(res.title, res.message, res.succes);
                if (form.data('posthangler') === true) {
                    posthangler(form, res);
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    }

    return false;
}

function editOrder(e) {
    var formData = $(this).serialize(),
            action = $(this).attr('action'),
            form = $(this);
    if (formData) {
        $.ajax({
            url: action,
            data: formData,
            type: 'POST',
            dataType: "json",
            success: function (res) {
                //console.log(res);
                addMessege(res.title, res.message, res.succes);
                if (res.newproduct === true) {
                    cartUpdate(res.id);
                }
                content = '<label>Данные заказа</label>';
                content = content + '<textarea id="text-aria-kurier" class="form-control" rows="15">' + res.kurier + '</textarea>';
                $(".data-kurier").html(content);
            },
            error: function (e) {
                console.log(e);
            }
        });
    }
    return false;
}

function editLastmodified(e) {
    var formData = $(this).serialize(),
            action = $(this).attr('action'),
            form = $(this);
    if (formData) {
        $.ajax({
            url: action,
            data: formData,
            type: 'POST',
            dataType: "json",
            success: function (res) {
                //console.log(res);
                addMessege(res.title, res.message, res.succes);
                $("#update_at" + res.id).text(res.update_at);
            },
            error: function (e) {
                console.log(e);
            }
        });
    }
    return false;
}

function cartUpdate(id) {
    var div = $(".cart-order-body");
    // console.log(adminpath + '/order/cart');

    $.ajax({
        url: adminpath + '/order/cart',
        data: {id: id},
        type: 'POST',
        dataType: "html",
        success: function (res) {
            //console.log(res);
            div.html(res);
            $("#newcartproduct").select2({
                placeholder: "Начните вводить наименование товара",
                //minimumInputLength: 2,
                cache: true,
                ajax: {
                    url: adminpath + "/order/new-product",
                    delay: 250,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        //console.log(data);
                        return {
                            results: data.items
                        };
                    }
                }
            });
        },
        error: function (e) {
            console.log(e);
        }
    });
}



function posthangler(obj, data) {
    if (obj.data('act') == 'addmodif') {
        $('#myTabmodification').append(data.li);
        $('#myTabContentmodification').append(data.content);
        $('.fileupload').each(function () {
            $(this).fileupload({
                dropZone: $(this),
                dataType: 'json',
                singleFileUploads: false,
                add: function (e, data) {
                    $(this).closest('.box').find('.overlay').css({'display': 'block'});
                    //console.log(data);
                    data.submit();
                },
                done: function (e, data) {
                    var div = $('#multi' + $(this).data('id'));
                    console.log(data.result);
                    $.each(data.result, function (index, file) {
                        content = addMulti(file);
                        $('#multi' + file.id).append(content);
                    });
                    $(this).closest('.box').find('.overlay').css({'display': 'none'});
                    addMessege('Изображения добавлены', 'Изображения в количестве ' + data.result.length + ' - успешно добавлены');
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress' + $(this).data('id') + ' .bar').css(
                            'width',
                            progress + '%'
                            );
                }
            });
        });
        // console.log(data.content);
    }
}

/* Search */

var acquirers = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        wildcard: '%QUERY',
        url: adminpath + '/order/typeahead?query=%QUERY'
    }
});
acquirers.initialize();
$("#typeahead").typeahead({
// hint: false,
    highlight: true
}, {
    name: 'acquirers',
    display: 'phone',
    limit: 9,
    source: acquirers
});
$('#typeahead').bind('typeahead:select', function (ev, suggestion) {
// console.log(suggestion);
    window.location = adminpath + '/order?acquirers_id=' + encodeURIComponent(suggestion.id);
});
var orders = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        wildcard: '%QUERY',
        url: adminpath + '/order/typeaheadordernum?query=%QUERY'
    }
});
orders.initialize();
$("#typeaheadordernum").typeahead({
// hint: false,
    highlight: true
}, {
    name: 'orders',
    display: 'order_num',
    limit: 10,
    source: orders
});
$('#typeaheadordernum').bind('typeahead:select', function (ev, suggestion) {
// console.log(suggestion);
    window.location = adminpath + '/order?order_id=' + encodeURIComponent(suggestion.id);
});

var product = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        wildcard: '%QUERY',
        url: adminpath + '/product/typeahead?query=%QUERY'
    }
});
product.initialize();
$("#typeaheadproductsku").typeahead({
// hint: false,
    highlight: true
}, {
    name: 'product',
    display: 'title',
    limit: 10,
    source: product
});
$('#typeaheadproductsku').bind('typeahead:select', function (ev, suggestion) {
// console.log(suggestion);
    window.location = adminpath + '/product?sku=' + encodeURIComponent(suggestion.sku);
});

var productid = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        wildcard: '%QUERY',
        url: adminpath + '/product/typeaheadproduct?query=%QUERY'
    }
});
productid.initialize();
$("#typeaheadproductid").typeahead({
// hint: false,
    highlight: true
}, {
    name: 'productid',
    display: 'title',
    limit: 10,
    source: productid
});
$('#typeaheadproductid').bind('typeahead:select', function (ev, suggestion) {
// console.log(suggestion);
    window.location = adminpath + '/product?product_id=' + encodeURIComponent(suggestion.id);
});

var utmid = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        wildcard: '%QUERY',
        url: adminpath + '/utm/typeahead?query=%QUERY'
    }
});
utmid.initialize();
$("#typeaheadutmid").typeahead({
    highlight: true
}, {
    name: 'utmid',
    display: 'key_item',
    limit: 10,
    source: utmid
});
$('#typeaheadutmid').bind('typeahead:select', function (ev, suggestion) {
// console.log(suggestion);
    window.location = adminpath + '/utm?id=' + encodeURIComponent(suggestion.id);
});

function checkForm(form) {
    console.log(form);
    return false;
}



//===============Delivery================

var cityselect = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        wildcard: '%QUERY',
        url: path + '/delivery/typeahead?query=%QUERY'
    }
});
cityselect.initialize();


function setDivelTeahead() {

    var cssInputSelector = '#city_id_selector';

//    $(cssInputSelector + 'close').on('click', function () {
//        $(cssInputSelector).val('');
//    });


    // console.log('setDivelTeahead', oldval, cssDivSelector)

    $(cssInputSelector).typeahead({
        // hint: false,
        minLength: 3,
        highlight: true
    }, {
        name: 'cityselect',
        display: 'CityName',
        limit: 20,
        source: cityselect,
        templates: {
            empty: [
                '<div class="empty-message">',
                'Сожалеем, ни чего не найдено',
                '</div>'
            ].join('\n'),
            suggestion: function (s) {

                return '<div>' + s.CityName + ' - ' + s.OblName + '</div>';
            }

        }

    });

    $(cssInputSelector).on('typeahead:select', function (ev, suggestion) {

        console.log(suggestion);


        $('#city_id').val(suggestion.ID);
        $('#city_name').val(suggestion.CityName);

    });

}

