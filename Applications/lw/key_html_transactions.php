<style>

    .bootstrap-lw #Label {
        position: absolute;
        top: 0px;
        left: 5%;
        echo "tpath=$tpath";
        height: 500px;
        width: 500px;
        z-index: 99;
    }
    .bootstrap-lw .sort {
        position: relative;
    }

    .bootstrap-lw .sort:after, .bootstrap-lw .sort:before {
        content: "";
        border: solid white;
        border-width: 0 2px 2px 0;
        display: inline-block;
        padding: 3px;
        /*margin-left: 10px;*/
        position: absolute;
        right: 10px;
        border-color: gray;

    }

    .bootstrap-lw .sort:before {
        top: 15px;
        transform: rotate(-135deg);
        -webkit-transform: rotate(-135deg);
    }

    .bootstrap-lw .sort:after {
        transform: rotate(45deg);
        -webkit-transform: rotate(45deg);
        bottom: 15px;
    }

    .bootstrap-lw .sort.down:after {
        border-color: white;
    }

    .bootstrap-lw .sort.up:before {
        border-color: white;
    }


    @media (min-width: 576px) {
        .bootstrap-lw .modal.fullscreen .modal-dialog {
            max-width: none;
        }
    }

    .bootstrap-lw .modal.fullscreen .modal-dialog {
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 0;
    }

    .bootstrap-lw .modal.fullscreen .modal-content {
        height: 100%;
    }

    .bootstrap-lw .modal.fullscreen .modal-footer {
        justify-content: center;
    }

    #windowEditModal {
        padding-right: 0 !important;
    }

    #windowEditModal .modal-content {
        border: none;
        border-radius: 0;
    }

    #closeEditModal {
        display: none;
    }

    .bootstrap-lw .totals td {
        text-align: right;
    }

    .bootstrap-lw .transactions-table .file-zip-icon {
        font-size: 17px;
    }
</style>

<script>
    $('#history-back', window.parent.document).prop('disabled', true);
</script>

<?php
//echo "<div ondblclick=\"window.location = '$url';\" id=spec width=100% width=100% >";
$account = $_GET['acc'];

$account = str_replace("/", ".", $account); // IF WE HAVE PROBLEMS HERE IT IS, THIS IS A TEMP FIX FOR UNESCABABLE SLASHES, IF ANY FUTURE CODER FINDS THIS AND IT CAUSE HEADACHE, HERE IS MY HOME ADDRESS SO YOU CAN MURDER ME IN MY SLEEP:

$regex = "^($account|$account:)" . '$';
$regex = "/^$account(:.*)?$/";
$regex = "/^$account(:.*)?$/";

?>

<?php $readonly = readonly(); ?>

<script>
    $(document).ready(function () {

        $(".file-zip-icon").append(fileZip);

        //format numbers
        $("tbody:first > tr:visible").slice(1).each(function () {
            val = parseInt(getCellValue(this, -2).replace(/\./g, ''));
            setCellValue(this, -2, number_format(val, 2, ',', '.'));
            val2 = parseInt(getCellValue(this, -1).replace(/\./g, ''));
            setCellValue(this, -1, number_format(val2, 2, ',', '.'));
        });

        //filter
        $('table tr:first').clone(true).prependTo('table');

        var filters = Array($('table tr:first td').length).fill([]);
        var $rows = $("tbody:first > tr").slice(2);

        $('table tr:eq(1) td:last').text("");
        $('table tr:eq(1) td:not(:last)').each(function (i) {
            var title = $(this).text();

            $(this).html('<input type="text" placeholder="Filter ' + title + '" />');

            $('input', this).on('keyup change', function () {
                var col = null;

                $("table tr:eq(0) td").each(function (i) {
                    if ($.trim($(this).text()) == title) {
                        col = i;
                        return false;
                    }
                });
                filters[col] = $(this).val().trim().replace(/ +/g, ' ').toLowerCase().split(",").filter(l => l.length);
                $rows.show();
                if (filters.some(f => f.length)) {
                    $rows.filter(function () {
                        var texts = $(this).children().map((i, td) => $(td).text().replace(/\s+/g, ' ').toLowerCase()).get();
                        return !texts.every((t, col) => {
                            return filters[col].length == 0 || filters[col].some((f, i) => t.indexOf(f) >= 0);
                        })
                    }).hide();
                }

                calculateTotals();
            });
        });

            jQuery.fn.sortTable = function() {
                var table = $(this[0]) // This is the element

                var headers = table.find('tr:first td:not(:last)');
                headers.addClass('sort').append(openedLockSvg).append(closedLockSvg);

                headers.css('cursor', 'pointer').click(function (e) {

                    var clickedHeader = $(this);
                    var clickedIndex = $(this).index();
                    var secondarySortIndex = -1;

                    var advSearch = false;

                    if(table.isBlocked){
                        var secondarySortChange = false;

                        if(table.isBlocked.column !== clickedIndex){
                            //secondarySort change
                            advSearch = true;
                            secondarySortChange = true;
                            secondarySortIndex = clickedIndex;
                        } else {
                            //primarySort change
                            var secondarySortIndex = headers.parent().find('.down, .up').not('.blocked').index();
                            if(secondarySortIndex >= 0){
                                advSearch = true;
                            }
                        }
                    }

                    if(advSearch){
                        var blockedHeader = table.find('.blocked');

                        var blockedSortDir  = blockedHeader.hasClass('down')?0:1;
                        var secondarySortDir = headers.eq(secondarySortIndex).hasClass('up')?1:0; //revers

                        if(secondarySortChange){
                            secondarySortDir = !secondarySortDir;
                        } else {
                            blockedSortDir = !blockedSortDir
                        }

                        var rows = table.find('tr:gt(1)').toArray().sort(advComparer(secondarySortIndex, table.isBlocked.column, secondarySortDir, blockedSortDir));

                        if(secondarySortChange){
                            headers.each(function () {
                                $(this).not('.blocked').removeClass('up down');
                            });
                            if (secondarySortDir) {
                                clickedHeader.addClass('up');
                            } else {
                                clickedHeader.addClass('down');
                            }
                        } else {
                            blockedHeader.removeClass('up down');

                            if (blockedSortDir) {
                                blockedHeader.addClass('up');
                            } else {
                                blockedHeader.addClass('down');
                            }
                        }
                    } else {
                        //basic sort
                        var sortDir  =  $(this).hasClass('up')?1:0;

                        headers.each(function () {
                            $(this).removeClass('up down');
                        });

                        var rows = table.find('tr:gt(1)').toArray().sort(comparer(clickedIndex));

                        if (!sortDir) {
                            $(this).addClass('up');

                        } else {
                            $(this).addClass('down');
                            rows = rows.reverse()
                        }
                    }

                    for (var i = 0; i < rows.length; i++) {
                        table.append(rows[i])
                    }
                    calculateTotals();
                });


                $('.opened-lock-icon').on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    $(this).parent().addClass('blocked');

                    table.addClass('advSort');
                    table.isBlocked = {
                        column:  $(this).parent().index()
                    };

                });
                $('.closed-lock-icon').on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    headers.each(function () {
                        $(this).not('.blocked').removeClass('up down');
                    });

                    $(this).parent().removeClass('blocked');
                    table.removeClass('advSort');
                    table.isBlocked = false;
                });

                return this; // This is needed so other functions can keep chaining off of this
            };

            $('table').sortTable();


        // console.log('not dev only');
        // //sort
        // $('table tr:first td:not(:last)').addClass('sort');
        // $('table tr:first td:not(:last)').css('cursor', 'pointer').click(function (e) {
        //
        //     $('table tr:first td').removeClass('up down');
        //     var table = $(this).parents('table').eq(0);
        //     var rows = table.find('tr:gt(1)').toArray().sort(comparer($(this).index()));
        //     this.asc = !this.asc;
        //     console.log($(this));
        //     console.log(this);
        //
        //
        //     if (!this.asc) {
        //         $(this).addClass('up');
        //         rows = rows.reverse()
        //     } else {
        //         $(this).addClass('down');
        //     }
        //     for (var i = 0; i < rows.length; i++) {
        //         table.append(rows[i])
        //     }
        //     calculateTotals();
        // });
        // $('.opened-lock-icon').on('click', function (e) {
        //     e.preventDefault();
        //     e.stopPropagation();
        //     console.log('---');
        //     console.log($(this));
        //     // $('table tr:first td.blocked').length
        //
        //     $(this).parent().addClass('blocked');
        // });
        // $('.closed-lock-icon').on('click', function (e) {
        //     e.preventDefault();
        //     e.stopPropagation();
        //     console.log('---');
        //     console.log($(this));
        //     $(this).parent().removeClass('blocked');
        // })




    });
</script>
<?php

browse($file, "reg \"$regex\"", $begin, $end, $beginytd, $endytd, $tpath, $_GET['page']);

?>


    <div class="modal fade fullscreen new-form" id="windowEditModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <?php if($readonly): ?>
                        <h5 class="modal-title" id="commentsModalLabel">Transaction</h5>
                    <?php else: ?>
                        <h5 class="modal-title" id="commentsModalLabel">Edit Transaction</h5>
                    <?php endif; ?>
                    <button id="closeEditTransactionModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="text-align: left; height: auto; overflow: auto;">
                    <div id="editTransactionForm" class="alpaca-form-container"></div>
                    <div style="visibility: hidden; height: 0">
                        <form id="fileForm" target="_blank" action="key_html_pdf.php" method="POST">
                            <input type="hidden" name="file" value=""/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Confirm File Delete Modal -->
    <div id="confirm-delete-file" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="display: flex; flex-direction: column;">
                    <p class="file-name">...</p>
                    <p>Are you sure you want to delete this file?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <?php

// gnuplot image - DO NOT REMOVE
//	require_once("/svn/svnroot/Applications/key_plot.php");
//	plot($tpath,$regex,$begin,$end,$beginytd,$endytd,$_GET['page']);

    echo "</div>";

    ?>

    <script>
        //show modal window
        $('#windowEditModal').on('show.bs.modal', function (event) {
            $('#history-back', window.parent.document).prop('disabled', false);
            var button = $(event.relatedTarget); // Button that triggered the modal
            var pageUrl = button.data('url');
            var modal = $(this);
            // modal.find('.modal-body').html('<iframe id="mainEditIframe" style="border: 0px; " src="' + pageUrl + '" width="100%" height="100%"></iframe>');

            urlParams = new URLSearchParams(pageUrl);

            const
                keys = urlParams.keys(),
                values = urlParams.values(),
                entries = urlParams.entries();

            $.get( pageUrl + "&req=getJsonToEdit", function( response ) {

                response = JSON.parse(response);
                var data = JSON.parse(response.fileData);
                var readonly = response.readonly;

                var formData = {
                    "info": {},
                    'Transactions': []
                };

                console.log('++++=====+++++===');
                console.log(data);

                //todo use schema to make this dynamic
                formData.info.Filename = data.Filename;
                formData.info.Description =  data.Description;
                formData.info.Ref =  data.Ref;
                formData.info.Reference =  data.Reference;
                formData.info.UID =  data.UID;
                formData.info.Date =  data.Date;
                formData.info.Comment =  data.Comment;

                formData.Transactions = data.Transactions;

                for(var i = 0; i < data.Transactions.length; i++){
                    var showPFields = false;
                    formData.Transactions[i]['pfields'] = {};
                    if (data.Transactions[i].hasOwnProperty('P-Start')) {
                        formData.Transactions[i]['pfields']["P-Start"] = data.Transactions[i]["P-Start"];
                        showPFields = true;
                    }
                    if (data.Transactions[i].hasOwnProperty('P-End')) {
                        formData.Transactions[i]['pfields']["P-End"] = data.Transactions[i]["P-End"];
                        showPFields = true;
                    }
                    if(showPFields){
                        formData.Transactions[i]['pfields'].showPFields = true;
                    }
                }

                formData.Files = data.Filereferences;

                $("#editTransactionForm").alpaca({
                    "schema": formSchema,
                    "data" : formData,
                    "options": {
                        "fields": (readonly) ? formOptions_fields_readonly : formOptions_fields ,
                        "form": {
                            // "toggleSubmitValidState": false, //validate on click
                            "attributes": {
                                "method": "post",
                                "action": "",
                                "enctype": "multipart/form-data",
                                "id": "edit-form-horizontal"
                            },
                            "buttons": {
                                "submit": {
                                    "title": "Save",
                                    "id" : "edit-form-submit",
                                    'styles': 'btn btn-dark',
                                    "click": function (e) {

                                        var value = this.getValue();

                                        var data = {};

                                        for (i in value.info) {
                                            value[i] = value.info[i];
                                        }
                                        delete value.info;

                                        for (j in value.Transactions) {
                                            if (value.Transactions[j]['pfields'].showPFields == true) {
                                                delete value.Transactions[j]['pfields'].showPFields;
                                                for (p in value.Transactions[j]['pfields']) {
                                                    value.Transactions[j][p] = value.Transactions[j]['pfields'][p];
                                                }
                                            }
                                            delete value.Transactions[j]['pfields'];
                                        }

                                        var form_data = new FormData();

                                        data['fileEdit'] = value;
                                        appendFormdata(form_data, data);

                                        var file_data = $("#file").prop("files");

                                        if(file_data.length){

                                            var filesArray = [];

                                            for (i = 0; i < file_data.length; i++) {
                                                filesArray.push(file_data[i]);
                                                form_data.append("filesToUpload[]", file_data[i]);
                                            }
                                        }

                                        if($('#delete-file-field').val()){
                                            form_data.append("deleteFile", $('#delete-file-field').val());
                                        }


                                        form_data.set("req", "fileForm-edit-new");

                                        var config = {
                                            // dataType: 'script',
                                            dataType: 'text',
                                            cache: false,
                                            contentType: false,
                                            processData: false,
                                            data: form_data,                         // Setting the data attribute of ajax with file_data
                                            type: 'post'
                                        };

                                        var promise = this.ajaxSubmit(config);

                                        promise.done(function (d) {

                                            d = JSON.parse(d);

                                            var message = "File: " + d.fileName + " saved!";

                                            if($('input[name ="save_and_hide"]').val()){
                                                window.parent.$('#windowModal').modal('hide');
                                                showMessage('alert-success', message, true);
                                            } else {
                                                showMessage('alert-success', message);

                                                if(d.newData !== undefined && d.newData.Filereferences !== undefined){
                                                    for(var i = 0; i < d.newData.Filereferences.length; i++){
                                                        var filepath = d.newData.Filereferences[i].filepath;

                                                        if(!$('#f-'+ filepath.replace('.', '-')).length){
                                                            getThumb(filepath, readonly);
                                                        }
                                                    }
                                                }
                                                if(d.removedFile !== undefined){
                                                    var fileName = d.removedFile;
                                                    $('#f-'+ fileName.replace('.', '-')).remove();
                                                    $('#confirm-delete-file').modal('hide');
                                                }
                                            }
                                        });
                                        promise.fail(function () {
                                            showMessage('alert-danger', "File not saved!");
                                        });
                                        promise.always(function () {
                                            //alert("Completed");
                                        });
                                    }
                                },
                                "submit_hide": {
                                    "title": "Save and hide [ENTER]",
                                    'styles': 'btn btn-dark',
                                    "click": function (e) {
                                        onSaveAndHide();
                                    }
                                },
                            }
                        }
                    },
                    "view": formViewEdit,
                    "postRender": function (control) {

                        if(readonly){
                            control.form.getButtonEl("submit_hide").remove();
                            control.form.getButtonEl("submit").remove();
                        }

                        var formContainer = $(control.domEl).find('form >.container');

                        var transactionsLegend = $(".transactions-array legend");
                        var transactionCheck = $("<span></span>");
                        transactionsLegend.append(transactionCheck);

                        updateTransactionsAmount(1);

                        var filesList = $("<div id='files-list' class='row files-list'></div>");

                        var label = "<label class=\"control-label alpaca-control-label col-12\" >Files</label>";
                        filesList.append(label);
                        formContainer.append(filesList);


                        console.log('==============================================');
                        console.log(formData.Files);
                        if(formData.Files){
                            // view thumbnails
                            for(var i = 0; i < formData.Files.length; i++){
                                var filepath = formData.Files[i].filepath;
                                getThumb(filepath, readonly);
                            }
                        }

                        if(!readonly) {

                            $(formContainer).on('keypress',function(e) {
                                if(e.which == 13) {
                                    e.preventDefault();
                                    onSaveAndHide();
                                }
                            });


                            var fileUpload = "<div class='row'><div class=\"upload-box\">\n" +
                                "                    <div class=\"box__input\">\n" +
                                "                        <label for=\"file\"><strong>Choose a file</strong><span\n" +
                                "                                    class=\"box__dragndrop\"> or drag it here</span>.</label>\n" +
                                "                        <input class=\"box__file\" type=\"file\" name=\"filesToUpload[]\" id=\"file\"\n" +
                                "                               data-multiple-caption=\"{count} files selected\" multiple/>\n" +
                                "                    </div>\n" +
                                "                    <div class=\"box__uploading\">Uploading&hellip;</div>\n" +
                                "                    <div class=\"box__success\">Done!</div>\n" +
                                "                    <div class=\"box__error\">Error! <span></span>.</div>\n" +
                                "                </div></div>";

                            formContainer.append(fileUpload);

                            var fileDelete = " <input class=\"\" type=\"hidden\" name=\"deleteFile\" id=\"delete-file-field\"/>";

                            formContainer.append(fileDelete);
                        }

                        $('.files-list').on('click', '.show-pdf-modal', function (e) {
                            e.preventDefault();



                            $('#fileForm input').val($(this).attr('data-file'));
                            $('#fileForm').submit().hide();
                        });

                        $('#confirm-delete-file').on('show.bs.modal', function (e) {
                            var data = $(e.relatedTarget).data();
                            $('.file-name', this).text(data.file);
                        });

                        $('#confirm-delete-file').on('click', '.btn-ok', function (e) {
                            var file = $('#confirm-delete-file .file-name').text();
                            $("#delete-file-field").prop("value", file);
                            $('#edit-form-submit').click();
                            // var $form = $('#edit-form-horizontal');
                            // $form.submit();
                        });

                        var isAdvancedUpload = function () {
                            var div = document.createElement('div');
                            return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
                        }();

                        var $form = $('#newTransactionForm form');
                        var $box = $('.upload-box');

                        if (isAdvancedUpload) {
                            $box.addClass('has-advanced-upload');
                        }

                        if (isAdvancedUpload) {
                            var droppedFiles = false;

                            var $input = $form.find('input[type="file"]'),
                                $label = $form.find('label[for="file"]'),
                                showFiles = function (files) {
                                    $("input[type='file']").prop("files", files);
                                    // $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
                                };

                            $box.on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
                                e.preventDefault();
                                e.stopPropagation();
                            })
                                .on('dragover dragenter', function () {
                                    $box.addClass('is-dragover');
                                })
                                .on('dragleave dragend drop', function () {
                                    $box.removeClass('is-dragover');
                                })
                                .on('drop', function (e) {
                                    droppedFiles = e.originalEvent.dataTransfer.files;
                                    showFiles(droppedFiles);

                                });
                        }

                        $form.on('submit', function (e) {

                            if (droppedFiles.length || $("input[type='file']").prop("files").length) {
                                if ($box.hasClass('is-uploading')) return false;

                                $box.addClass('is-uploading').removeClass('is-error');

                                if (isAdvancedUpload) {
                                    if (droppedFiles) {
                                        $("input[type='file']").prop("files", droppedFiles);
                                    }
                                } else {
                                    //TODO
                                    // ajax for legacy browsers
                                    var iframeName = 'uploadiframe' + new Date().getTime();
                                    $iframe = $('<iframe name="' + iframeName + '" style="display: none;"></iframe>');

                                    $('body').append($iframe);
                                    $form.attr('target', iframeName);

                                    $iframe.one('load', function () {
                                        var data = JSON.parse($iframe.contents().find('body').text());
                                        $form
                                            .removeClass('is-uploading')
                                            .addClass(data.success == true ? 'is-success' : 'is-error')
                                            .removeAttr('target');
                                        if (!data.success) $errorMsg.text(data.error);
                                        $form.removeAttr('target');
                                        $iframe.remove();
                                    });
                                }
                            } else {
                                $("input[type='file']").remove();
                            }
                        });

                    }
                });
            });
        });

        $('#windowEditModal').on('hide.bs.modal', function (event) {
            $('#history-back', window.parent.document).prop('disabled', true);
        });

    </script>

