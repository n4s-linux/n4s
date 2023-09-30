
<!--<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>-->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<!--    dependency for alpaca -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous"></script>-->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/0.11.1/bloodhound.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/0.10.5/typeahead.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-price-format/2.2.0/jquery.priceformat.min.js" integrity="sha512-qHlEL6N+fxDGsJoPhq/jFcxJkRURgMerSFixe39WoYaB2oj91lvJXYDVyEO1+tOuWO+sBtUGHhl3v3hUp1tGMA==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js" integrity="sha512-Izh34nqeeR7/nwthfeE0SI3c8uhFSnqxV0sI9TvTcXiFJkMd6fB644O64BRq2P/LA/+7eRvCw4GmLsXksyTHBg==" crossorigin="anonymous"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-file-upload/4.0.11/jquery.uploadfile.min.js" integrity="sha512-uwNlWrX8+f31dKuSezJIHdwlROJWNkP6URRf+FSWkxSgrGRuiAreWzJLA2IpyRH9lN2H67IP5H4CxBcAshYGNw==" crossorigin="anonymous"></script>-->
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">


<!--    alpaca-->
<link type="text/css" href="//cdn.jsdelivr.net/npm/alpaca@1.5.27/dist/alpaca/bootstrap/alpaca.min.css" rel="stylesheet"/>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/alpaca@1.5.27/dist/alpaca/bootstrap/alpaca.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>


<script>


    var openedLockSvg = "<svg class='opened-lock-icon' version=\"1.1\" id=\"Capa_1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\"\n" +
        "\t viewBox=\"0 0 320 320\" style=\"enable-background:new 0 0 320 320;\" xml:space=\"preserve\">\n" +
        "<g>\n" +
        "\t<path d=\"M160,103.695c-18.802,0-36.461,5.146-51.731,14.14V64.523c0-24.551,18.025-44.523,40.183-44.523h23.098\n" +
        "\t\tc22.157,0,40.183,19.973,40.183,44.523c0,5.522,4.478,10,10,10s10-4.478,10-10C231.731,28.945,204.733,0,171.549,0h-23.098\n" +
        "\t\tc-33.185,0-60.183,28.945-60.183,64.523v68.591c-20.308,19.738-33.011,47.731-33.011,78.733C55.258,271.483,102.245,320,160,320\n" +
        "\t\ts104.742-48.517,104.742-108.152S217.755,103.695,160,103.695z M160,300c-46.727,0-84.742-39.545-84.742-88.152\n" +
        "\t\ts38.016-88.152,84.742-88.152s84.742,39.545,84.742,88.152S206.727,300,160,300z\"/>\n" +
        "\t<path d=\"M189.504,195.563c0-16.273-13.235-29.513-29.505-29.513c-16.268,0-29.503,13.239-29.503,29.513\n" +
        "\t\tc0,8.318,3.452,16.06,9.343,21.557l-7.075,31.729c-0.159,0.715-0.239,1.444-0.239,2.177c0,8.667,8.488,15.202,19.744,15.202h15.467\n" +
        "\t\tc11.254,0,19.74-6.535,19.74-15.202c0-0.732-0.08-1.462-0.24-2.177l-7.076-31.729C186.051,211.622,189.504,203.881,189.504,195.563\n" +
        "\t\tz M153.84,246.227l6.159-27.622l6.161,27.622H153.84z M164.36,204.014c-1.944,1.01-3.443,2.591-4.361,4.455\n" +
        "\t\tc-0.918-1.864-2.417-3.445-4.361-4.455c-3.171-1.647-5.142-4.886-5.142-8.451c0-5.245,4.263-9.513,9.503-9.513\n" +
        "\t\tc5.241,0,9.505,4.268,9.505,9.513C169.504,199.127,167.533,202.365,164.36,204.014z\"/>\n" +
        "</g>\n" +
        "\n" +
        "</svg>";

    var closedLockSvg ="<svg class='closed-lock-icon' version=\"1.1\" id=\"Capa_1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\"\n" +
        "\t viewBox=\"0 0 320 320\" style=\"enable-background:new 0 0 320 320;\" xml:space=\"preserve\">\n" +
        "<g>\n" +
        "\t<path d=\"M231.731,133.115V64.523C231.731,28.945,204.733,0,171.549,0h-23.098c-33.185,0-60.183,28.945-60.183,64.523v68.591\n" +
        "\t\tc-20.308,19.738-33.011,47.731-33.011,78.733C55.258,271.483,102.245,320,160,320s104.742-48.517,104.742-108.152\n" +
        "\t\tC264.742,180.846,252.04,152.853,231.731,133.115z M108.269,117.804v-53.28c0-24.551,18.025-44.523,40.183-44.523h23.098\n" +
        "\t\tc22.157,0,40.183,19.973,40.183,44.523v53.28c-2.73-1.601-5.528-3.063-8.38-4.402c-0.102-0.048-0.203-0.099-0.305-0.147\n" +
        "\t\tc-0.399-0.186-0.801-0.363-1.202-0.544c-0.596-0.269-1.195-0.534-1.796-0.792c-0.282-0.12-0.563-0.24-0.846-0.358\n" +
        "\t\tc-0.891-0.373-1.787-0.734-2.689-1.082c-0.02-0.008-0.04-0.016-0.059-0.023c-9.231-3.55-19.073-5.789-29.314-6.504\n" +
        "\t\tc-0.237-0.017-0.474-0.032-0.711-0.047c-0.82-0.052-1.643-0.093-2.468-0.125c-0.271-0.011-0.542-0.024-0.814-0.032\n" +
        "\t\tc-1.046-0.032-2.094-0.053-3.147-0.053s-2.101,0.021-3.147,0.053c-0.272,0.008-0.543,0.021-0.814,0.032\n" +
        "\t\tc-0.825,0.032-1.648,0.073-2.468,0.125c-0.237,0.015-0.474,0.03-0.711,0.047c-10.241,0.715-20.083,2.954-29.314,6.504\n" +
        "\t\tc-0.02,0.008-0.04,0.016-0.059,0.023c-0.903,0.348-1.799,0.709-2.689,1.082c-0.283,0.118-0.564,0.237-0.846,0.358\n" +
        "\t\tc-0.602,0.258-1.2,0.523-1.796,0.792c-0.401,0.18-0.803,0.358-1.202,0.544c-0.102,0.048-0.203,0.099-0.305,0.147\n" +
        "\t\tC113.797,114.74,110.998,116.203,108.269,117.804z M160,300c-46.727,0-84.742-39.545-84.742-88.152\n" +
        "\t\tc0-32.953,17.476-61.736,43.29-76.861c0.402-0.234,0.802-0.472,1.207-0.699c0.442-0.249,0.888-0.492,1.335-0.734\n" +
        "\t\tc0.784-0.422,1.572-0.836,2.368-1.232c0.252-0.126,0.507-0.247,0.761-0.37c8.831-4.279,18.342-6.968,28.119-7.892\n" +
        "\t\tc0.085-0.008,0.171-0.016,0.257-0.024c1.158-0.106,2.32-0.187,3.484-0.243c0.135-0.006,0.272-0.01,0.407-0.016\n" +
        "\t\tc1.169-0.051,2.341-0.082,3.515-0.082s2.346,0.031,3.515,0.082c0.136,0.006,0.272,0.01,0.407,0.016\n" +
        "\t\tc1.165,0.056,2.326,0.137,3.484,0.243c0.086,0.008,0.171,0.016,0.257,0.024c9.777,0.924,19.288,3.613,28.119,7.892\n" +
        "\t\tc0.254,0.123,0.509,0.244,0.761,0.37c0.796,0.396,1.583,0.81,2.368,1.232c0.447,0.241,0.893,0.484,1.335,0.734\n" +
        "\t\tc0.405,0.227,0.806,0.465,1.207,0.699c25.814,15.125,43.29,43.908,43.29,76.861C244.742,260.455,206.727,300,160,300z\"/>\n" +
        "\t<path d=\"M189.504,195.563c0-16.273-13.235-29.513-29.505-29.513c-16.268,0-29.503,13.239-29.503,29.513\n" +
        "\t\tc0,8.318,3.452,16.06,9.343,21.557l-7.075,31.729c-0.159,0.715-0.239,1.444-0.239,2.177c0,8.667,8.488,15.202,19.744,15.202h15.467\n" +
        "\t\tc11.254,0,19.74-6.535,19.74-15.202c0-0.732-0.08-1.462-0.24-2.177l-7.076-31.729C186.051,211.622,189.504,203.881,189.504,195.563\n" +
        "\t\tz M153.84,246.227l6.159-27.622l6.161,27.622H153.84z M164.36,204.014c-1.944,1.01-3.443,2.591-4.361,4.455\n" +
        "\t\tc-0.918-1.864-2.417-3.445-4.361-4.455c-3.171-1.647-5.142-4.886-5.142-8.451c0-5.245,4.263-9.513,9.503-9.513\n" +
        "\t\tc5.241,0,9.505,4.268,9.505,9.513C169.504,199.127,167.533,202.365,164.36,204.014z\"/>\n" +
        "</g>\n" +
        "</svg>";

    var fileZip = "<svg width=\"1em\" height=\"1em\" viewBox=\"0 0 16 16\" class=\"bi bi-file-zip\" fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\">\n" +
        "  <path fill-rule=\"evenodd\" d=\"M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H4z\"/>\n" +
        "  <path fill-rule=\"evenodd\" d=\"M6.5 7.5a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v.938l.4 1.599a1 1 0 0 1-.416 1.074l-.93.62a1 1 0 0 1-1.109 0l-.93-.62a1 1 0 0 1-.415-1.074l.4-1.599V7.5zm2 0h-1v.938a1 1 0 0 1-.03.243l-.4 1.598.93.62.93-.62-.4-1.598a1 1 0 0 1-.03-.243V7.5z\"/>\n" +
        "  <path d=\"M7.5 1H9v1H7.5zm-1 1H8v1H6.5zm1 1H9v1H7.5zm-1 1H8v1H6.5zm1 1H9v1H7.5V5z\"/>\n" +
        "</svg>";



    Alpaca.views["bootstrap-edit"].callbacks.required = function () {
        var fieldEl = this.getFieldEl();

        // required fields get a little star in their label
        //var label = $(fieldEl).find("label.alpaca-control-label");
        //$('<span class="alpaca-icon-required glyphicon glyphicon-star"></span>').prependTo(label);
        var label = $(fieldEl).find("label.alpaca-control-label");
        if ($(label).length > 0) {
            $(label).append("<span class='alpaca-required-indicator-new'>*</span>")
        }

    };

    var formSchema = {
        "type": "object",
        "properties": {
            "info" : {
                "type": "object",
                "properties": {
                    "Description": {
                        "type": "string",
                        "title": "Description"
                    },
                    "Ref": {
                        "type": "string",
                        "title": "Ref"
                    },
                    "Reference": {
                        "type": "string",
                        "title": "Reference"
                    },
                    "UID": {
                        "type": "string",
                        "title": "UID"
                    },
                    "Filename": {
                        "type": "string",
                        "title": "Filename"
                    },
                    "Date": {
                        "type": "string",
                        "title": "Date",
                        "format": "date",
                        "required": true,
                    },
                    "Comment": {
                        "type": "string",
                        "title": "Comment"
                    },
                    // "File": {
                    //     "type": "string",
                    //     "format": "uri"
                    // }
                }
            },
            "Transactions" : {
                "type": "array",
                "title": "Transactions",
                "items" : {
                    "type": "object",
                    'fieldClass': 'WWW',
                    "properties": {
                        "Account": {
                            "type": "string",
                            "title": "Account",
                            "required": true,
                        },
                        "Amount": {
                            // "type": "number",
                            "title": "Amount",
                            "required": true
                        },
                        "Func": {
                            "type": "string",
                            "title": "Func",
                            "required": false
                        },
                        'pfields': {
                            "type": "object",
                            "properties": {
                                "showPFields": {
                                    "type": "boolean",
                                    'fieldClass': 'XXX',
                                    // "title": "Show P-fields"
                                },
                                "P-Start": {
                                    "type": "string",
                                    "title": "P-Start"
                                },
                                "P-End": {
                                    "type": "string",
                                    "title": "P-End"
                                },
                            },
                            "dependencies": {
                                "P-Start": ["showPFields"],
                                "P-End": ["showPFields"],
                            }
                        },

                    },

                },
                "minItems": 2
            }

        },

    };

    var formOptions_fields =  {
        'Description': {
            "view": "bootstrap-edit-horizontal"
            // 'fieldClass': 'form-control-sm'
        },
        'info': {
            'fieldClass': 'info-container',
            'fields': {
                "Description": {
                    "events": {
                        "keyup": function () {
                            onFieldChange(this.domEl);
                        }
                    },
                },
                "Date": {
                    "type": "date",
                    "picker": {
                        "format": "YYYY-MM-DD"
                    },
                    "events": {
                        "keyup": function () {
                            onFieldChange(this.domEl);
                        }
                    },
                    "manualEntry": false //but it's true
                    ,"validator": function(callback) {
                        var value = this.getValue();

                        if (value === "" || moment(value, "YYYY-MM-DD",true).isValid()) {
                            callback({
                                "status": true
                            });

                        } else {
                            callback({
                                "status": false,
                                "message": "Date should be in YYYY-MM-DD format"
                            });
                        }
                    }
                },
            }
        },
        'Transactions': {
            "toolbarSticky": true,
            "collapsible": false,
            "hideToolbarWithChildren": false,
            'fieldClass': 'transactions-array',
            'id': 'AAAAAAAAAAAAAAAAAAAAAAAAAA11111',
            "actionbar": {
                "actions": [{
                    "action": "add",
                    "enabled": false
                }]
            },

            "items": {
                'fieldClass': 'transactions-container',
                'id': 'transaction-group',
                "fields": {
                    "Account": {
                        // "showMessages": false,
                        'fieldClass': 'account-field-with-dropdown',
                        "view": "web-display",
                        "hideInitValidationError": "true",
                        "typeahead": {
                            "config": {
                                "autoselect": true,
                                "highlight": true,
                                "hint": true,
                                "minLength": 1
                            },
                            "datasets": {
                                // "type": "remote",
                                // "source": "?accountNames=%QUERY",
                                "name": 'accountsData',
                                "source": accountsMatch(),
                                // "limit": 10
                            }
                        },

                    },
                    "Amount": {
                        // "showMessages": false,
                        "hideInitValidationError": true,
                        "type": "text",
                        "centsSeparator": ",",
                        "prefix": "",
                        "suffix": "",
                        "thousandsSeparator": ".",
                        "allowNegative": true,
                        "allowOptionalEmpty": true,
                        "fieldClass": "amount",
                        "events": {
                            "keyup": function () {
                                updateTransactionsAmount(this);
                            }
                        },
                        "validator": function (callback) {
                            var sum = getTransactionSum();

                            if (sum == 0) {
                                callback({
                                    "status": true
                                });
                            } else {
                                callback({
                                    "status": false,
                                    // "message": "Transactions sum must be 0!"
                                });
                            }

                        }
                    },
                    "Func": {
                        "showMessages": false,
                        "hideInitValidationError": true,
                        "allowOptionalEmpty": true,
                        "fieldClass": "func"
                    },
                    "pfields": {
                        'fieldClass': 'p-fields-container',
                        "fields": {
                            'fieldClass': 'BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB',
                            "showPFields": {
                                "rightLabel": 'Show P-fields',
                                "fieldClass": "show-p-fields"
                            },
                            'P-Start': {
                                "type": "date",
                                "picker": {
                                    "format": "YYYY-MM-DD"
                                },
                                "manualEntry": false //but it's true
                                ,"validator": function(callback) {
                                    var value = this.getValue();

                                    if (value === "" || moment(value, "YYYY-MM-DD",true).isValid()) {
                                        callback({
                                            "status": true
                                        });

                                    } else {
                                        callback({
                                            "status": false,
                                            "message": "Date should be in YYYY-MM-DD format"
                                        });
                                    }
                                }
                            },
                            'P-End': {
                                "type": "date",
                                "picker": {
                                    "format": "YYYY-MM-DD"
                                },
                                "manualEntry": false //but it's true
                                ,"validator": function(callback) {
                                    var value = this.getValue();

                                    if (value === "" || moment(value, "YYYY-MM-DD",true).isValid()) {
                                        callback({
                                            "status": true
                                        });

                                    } else {
                                        callback({
                                            "status": false,
                                            "message": "Date should be in YYYY-MM-DD format"
                                        });
                                    }
                                }
                            }
                        }
                    },

                }
            },
            "actionbar": {
                "actions": [{
                    "action": "add",
                    "enabled": false
                }]
            }
        }

    };

    var formOptions_fields_readonly =  {
        'Description': {
            "view": "bootstrap-edit-horizontal"
            // 'fieldClass': 'form-control-sm'
        },
        'info': {
            'fieldClass': 'info-container',
            'fields': {
                "Description": {
                    "readonly":true
                },
                "Ref": {
                    "readonly":true
                },
                "Reference": {
                    "readonly":true
                },
                "UID": {
                    "readonly":true
                },
                "Filename": {
                    "readonly":true
                },
                "Date": {
                    "type": "date",
                    "readonly":true
                },
                "Comment": {
                    "readonly":true
                }
            }
        },
        'Transactions': {
            "toolbarSticky": true,
            "collapsible": false,
            "hideToolbarWithChildren": false,
            'fieldClass': 'transactions-array',
            'id': 'AAAAAAAAAAAAAAAAAAAAAAAAAA11111',
            "actionbar": {
                "actions": [{
                    "action": "add",
                    "enabled": false
                }]
            },

            "items": {
                'fieldClass': 'transactions-container',
                'id': 'transaction-group',
                "fields": {
                    "Account": {
                        // "showMessages": false,
                        'fieldClass': 'YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY',
                        "view": "web-display",
                        "hideInitValidationError": "true",
                        "readonly": true
                    },
                    "Amount": {
                        // "showMessages": false,
                        "hideInitValidationError": true,
                        "type": "text",
                        "centsSeparator": ",",
                        "prefix": "",
                        "suffix": "",
                        "thousandsSeparator": ".",
                        "fieldClass": "amount",
                        "readonly": true
                    },
                    "Func": {
                        "showMessages": false,
                        "hideInitValidationError": true,
                        "allowOptionalEmpty": true,
                        "fieldClass": "func",
                        "readonly": true
                    },
                    "pfields": {
                        'fieldClass': 'p-fields-container',
                        "fields": {
                            'fieldClass': 'BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB',
                            "showPFields": {
                                "rightLabel": 'Show P-fields',
                                "fieldClass": "show-p-fields"
                            },
                            'P-Start': {
                                "type": "date",
                                "readonly": true
                            },
                            'P-End': {
                                "type": "date",
                                "readonly": true
                            }
                        }
                    },

                }
            }
        }
    };

    var formView = {
        // "globalTemplate": "#template5",
        "parent": "bootstrap-create-horizontal",
        "fields": {
            'Transactions': {
                // "parent" :  "bootstrap-create",
                // "globalTemplate" : "#template5"

            }
        },
        // "templates": {
        //     // "message": "<div style='text-align:center'><h3 style='color: red;'>Yippee kai yay!</h3><p style='color: red;'>{{{message}}}</p></div>"
        // },
        "layout": {
            "template": "#template6",
            "bindings": {
                "info": "#top",
                "Transactions": "#bottom"
            }
        }
    };

    var formViewEdit = {
        // "globalTemplate": "#template5",
        "parent": "bootstrap-edit-horizontal",
        "fields": {
            'transactions': {
                // "parent" :  "bootstrap-create",
                // "globalTemplate" : "#template5"

            }
        },
        // "templates": {
        //     // "message": "<div style='text-align:center'><h3 style='color: red;'>Yippee kai yay!</h3><p style='color: red;'>{{{message}}}</p></div>"
        // },
        "layout": {
            "template": "#template6",
            "bindings": {
                "info": "#top",
                "Transactions": "#bottom"
            }
        }
    };

    getAccountNames();

    function getAccountNames(){

        var yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);

        var now = new Date();

        if(localStorage.accountNamesUpdated && localStorage.accountNamesUpdated < yesterday.getTime()){
          // var updateDate = new Date(Number(localStorage.accountNamesUpdated));
        } else {
            $.post( "", { getData: "accountNames" }, function( data ) {
                localStorage.setItem('accountNames', JSON.stringify(data));
                localStorage.setItem('accountNamesUpdated', now.getTime());

            }, "json");
        }
    }

    function accountsMatch(){

        return function findMatches(q, cb) {

            var  strs = [];
            if(localStorage.accountNames){
                strs =   JSON.parse(localStorage.accountNames);
            } else {
                getAccountNames();
            }

            var matches, substringRegex;

            // an array that will be populated with substring matches
            matches = [];

            // regex used to determine if a string contains the substring `q`
            substrRegex = new RegExp(q, 'i');

            // iterate through the pool of strings and for any string that
            // contains the substring `q`, add it to the `matches` array
            var count = 0, limit = 10;
            $.each(strs, function(i, str) {
                if (substrRegex.test(str.name)) {
                    matches.push(str);
                    count++;
                }
                if(count == limit)  return false;
            });
            return cb(matches);
        };
    }

    var substringMatcher = function(strs) {

    };

    function appendFormdata(FormData, data, name){
        name = name || '';
        if (typeof data === 'object'){
            $.each(data, function(index, value){
                if (name == ''){
                    appendFormdata(FormData, value, index);
                } else {
                    appendFormdata(FormData, value, name + '['+index+']');
                }
            })
        } else {
            FormData.append(name, data);
        }
    }

    function onFieldChange(field) {
        var mainID =  $(field).parents('form').parent().attr('id');
        if(mainID == 'newTransactionForm') {
            updateFileName();
        }
    }
    function updateFileName(uid) {

        var dateField = $('[name="info_Date"]').val();
        var descField = $('[name="info_Description"]').val();
        var fileNameField =  $('[name="info_Filename"]');
        var fileName = fileNameField.val();

        if(fileName.length == 0){
            // uid = Math.random().toString(36).substr(2, 9);
            fileName = setFileName(dateField, descField, uid);
        } else {
            fileName = fileName.replace(".trans", "");
            fileNameArray = fileName.split("_");
            uid = fileNameArray[fileNameArray.length - 1];
            fileName = setFileName(dateField, descField, uid);

        }

        fileNameField.val(fileName);
    }

    function setFileName(date, desc, id){

        var filename = "";

        if(date.length) {
            date = date.split("-").join("");
            filename += date;
        }

        if(desc.length) {
            desc = desc.replace(/[^a-z0-9]/gi,'').toLowerCase();
            filename += "_"+desc;
        }

        if(id.length)
            filename += "_"+id;

        filename = filename + ".trans";
        return filename;

    }

    function onSaveAndHide(){
        var hide = $("<input>").attr("type", "hidden").attr("name", "save_and_hide").val("true");
        $('#editTransactionForm form').append($(hide));
        $('#editTransactionForm form #edit-form-submit').click();
    }


    function showhide() {
        return true;
        var x = document.getElementById("nav");
        if (x.style.display === "none") {
            x.style.width = "700";
            x.style.height = "900";
            x.style.left = 0;
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

    function number_format(number,decimals,dec_point,thousands_sep) {
        number  = number*1;//makes sure `number` is numeric value
        var str = number.toFixed(decimals?decimals:0).toString().split('.');
        var parts = [];
        for ( var i=str[0].length; i>0; i-=3 ) {
            parts.unshift(str[0].substring(Math.max(0,i-3),i));
        }
        if(parts[0] == "-"){
            parts.shift();
            str[0] = "-" + parts.join(thousands_sep?thousands_sep:',');
        } else {
            str[0] = parts.join(thousands_sep?thousands_sep:',');
        }


        return str.join(dec_point?dec_point:'.');
    }

    function comparer(index) {
        return function(a, b) {

            var valA = getCellValue(a, index), valB = getCellValue(b, index);

            valA = valA.replace(/\./g, '').replace(',', '.');
            valB = valB.replace(/\./g, '').replace(',', '.');

            return $.isNumeric(valA) && $.isNumeric(valB) ? valA.replace('.', '') - valB.replace('.', '') : valA.toString().localeCompare(valB)
        }
    }

    function advComparer(index, blockedIndex, dir, blockedDir) {

        return function(a, b) {

            var valA = getCellValue(a, index), valB = getCellValue(b, index);
            var blockedA = getCellValue(a, blockedIndex), blockedB = getCellValue(b, blockedIndex);

            valA = valA.replace(/\./g, '').replace(',', '.');
            valB = valB.replace(/\./g, '').replace(',', '.');
            blockedA = blockedA.replace(/\./g, '').replace(',', '.');
            blockedB = blockedB.replace(/\./g, '').replace(',', '.');

            var compare = 0, blockedCompare = 0;
            if(dir) {
                compare = $.isNumeric(valA) && $.isNumeric(valB) ? valA.replace('.', '') - valB.replace('.', '') : valA.toString().localeCompare(valB);
            } else {
                compare = $.isNumeric(valA) && $.isNumeric(valB) ? valB.replace('.', '') - valA.replace('.', '') : valB.toString().localeCompare(valA);
            }

            if(blockedDir){
                blockedCompare = $.isNumeric(blockedA) && $.isNumeric(blockedB) ? blockedA.replace('.', '') - blockedB.replace('.', '') : blockedA.toString().localeCompare(blockedB);
            } else {
                blockedCompare = $.isNumeric(blockedA) && $.isNumeric(blockedB) ? blockedB.replace('.', '') - blockedA.replace('.', '') : blockedB.toString().localeCompare(blockedA);
            }

            return blockedCompare || compare;

        }
    }

    function getCellValue(row, index){ return $(row).children('td').eq(index).text() }

    function setCellValue(row, index, value) {
        if($(row).children('td').eq(index).find('a').length){
            $(row).children('td').eq(index).find('a').text(value);
        } else {
            $(row).children('td').eq(index).find('p').text(value)
        }
        return
    }

    function calculateTotals() {
        var rows =  $("tbody:first > tr:visible").slice(2);
        var total = 0;

        rows.each(function () {
            val =  parseInt(getCellValue(this,-2).replace(/\./g, '').replace(',', '.'));
            total += val;
            setCellValue(this,-1,number_format(total, 2, ',', '.'))
        });
    }

    function baseName(str){
        var base = new String(str).substring(str.lastIndexOf('/') + 1);
        // if(base.lastIndexOf(".") != -1)
        //     base = base.substring(0, base.lastIndexOf("."));
        return base;
    }

    function showMessage(type, message, parent) {
       var w = window;
        if(parent) {
            w = window.parent;
       }
        w.$('.alert').removeClass('alert-success alert-danger alert-info').addClass(type).addClass('show').find('.message').text(message);
    }

    function hideMenu() {
        $('#collapseSidebarButton').html('<svg class="bi bi-chevron-double-left" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">\n' +
            '  <path fill-rule="evenodd" d="M8.354 1.646a.5.5 0 010 .708L2.707 8l5.647 5.646a.5.5 0 01-.708.708l-6-6a.5.5 0 010-.708l6-6a.5.5 0 01.708 0z" clip-rule="evenodd"/>\n' +
            '  <path fill-rule="evenodd" d="M12.354 1.646a.5.5 0 010 .708L6.707 8l5.647 5.646a.5.5 0 01-.708.708l-6-6a.5.5 0 010-.708l6-6a.5.5 0 01.708 0z" clip-rule="evenodd"/>\n' +
            '</svg>').blur();
        $('#printButton').html('<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 482.5 482.5" style="enable-background:new 0 0 482.5 482.5;" xml:space="preserve"><g><g>\n' +
            '        <path d="M399.25,98.9h-12.4V71.3c0-39.3-32-71.3-71.3-71.3h-149.7c-39.3,0-71.3,32-71.3,71.3v27.6h-11.3\n' +
            '\t\t\tc-39.3,0-71.3,32-71.3,71.3v115c0,39.3,32,71.3,71.3,71.3h11.2v90.4c0,19.6,16,35.6,35.6,35.6h221.1c19.6,0,35.6-16,35.6-35.6\n' +
            '\t\t\tv-90.4h12.5c39.3,0,71.3-32,71.3-71.3v-115C470.55,130.9,438.55,98.9,399.25,98.9z M121.45,71.3c0-24.4,19.9-44.3,44.3-44.3h149.6\n' +
            '\t\t\tc24.4,0,44.3,19.9,44.3,44.3v27.6h-238.2V71.3z M359.75,447.1c0,4.7-3.9,8.6-8.6,8.6h-221.1c-4.7,0-8.6-3.9-8.6-8.6V298h238.3\n' +
            '\t\t\tV447.1z M443.55,285.3c0,24.4-19.9,44.3-44.3,44.3h-12.4V298h17.8c7.5,0,13.5-6,13.5-13.5s-6-13.5-13.5-13.5h-330\n' +
            '\t\t\tc-7.5,0-13.5,6-13.5,13.5s6,13.5,13.5,13.5h19.9v31.6h-11.3c-24.4,0-44.3-19.9-44.3-44.3v-115c0-24.4,19.9-44.3,44.3-44.3h316\n' +
            '\t\t\tc24.4,0,44.3,19.9,44.3,44.3V285.3z"/>\n' +
            '        <path d="M154.15,364.4h171.9c7.5,0,13.5-6,13.5-13.5s-6-13.5-13.5-13.5h-171.9c-7.5,0-13.5,6-13.5,13.5S146.75,364.4,154.15,364.4\n' +
            '\t\t\tz"/>\n' +
            '        <path d="M327.15,392.6h-172c-7.5,0-13.5,6-13.5,13.5s6,13.5,13.5,13.5h171.9c7.5,0,13.5-6,13.5-13.5S334.55,392.6,327.15,392.6z"\n' +
            '        />\n' +
            '        <path d="M398.95,151.9h-27.4c-7.5,0-13.5,6-13.5,13.5s6,13.5,13.5,13.5h27.4c7.5,0,13.5-6,13.5-13.5S406.45,151.9,398.95,151.9z"\n' +
            '        /></g></g></svg>').blur();
        $('#p2Button').hide();
        $('.dev-switch').removeClass('d-flex').hide();
    }

    function showMenu(){
        $('#collapseSidebarButton').html('Hide').blur();
        $('#printButton').html('Print').blur();
        $('#p2Button').show();
        $('.dev-switch').addClass('d-flex').show();
    }

    function readCookie(name) {
        let key = name + "=";
        let cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            let cookie = cookies[i];
            while (cookie.charAt(0) === ' ') {
                cookie = cookie.substring(1, cookie.length);
            }
            if (cookie.indexOf(key) === 0) {
                let cookieString = cookie.substring(key.length, cookie.length);
                return JSON.parse(decodeURIComponent(cookieString));
            }
        }
        return null;
    }

    function createCookie(key, value) {
        let cookie = escape(key) + "=" + escape(value) + ";";
        document.cookie = cookie;
    }

    function updateTransactionsAmount(field) {

        if(field.name == "Transactions_0_Amount"){
            $(".transactions-array input[name='Transactions_1_Amount']").val(-field.getValue());
        }

        var transactionSumField = $(".transactions-array legend span").removeClass(['text-success', 'text-danger']);

        var sum = getTransactionSum();

        if(sum == 0) {
            transactionSumField.addClass('text-success');
        } else {
            transactionSumField.addClass('text-danger');
        }

        // val =  parseInt(getCellValue(this,-2).replace(/\./g, '').replace(',', '.'));
        // total += val;
        // setCellValue(this,-1,number_format(total, 2, ',', '.'));

        var transactionsLegend = transactionSumField.text("Sum: " + number_format(sum, 2, ',', '.'));
        var control = $(".alpaca-form-container").alpaca("get");

        control.form.refreshValidationState(true);

    }

    function getTransactionSum() {
        var sum = 0;
        $(".amount input").each(function(){

            sum += parseFloat($(this).val().replace(/\./g, '').replace(',', '.'));
        });
        return sum;
    }

    function generateLink(){
        $.post( "", { getData: "generateLink" }, function( data ) {

            $('#generateLinkModal').modal('show').find('#generatedLink').val(data.generatedLink);
        }, "json");

    }

    function updateLink() {

        var generatedInput = $('#generatedLink');

        generatedInput.addClass('hide-text');

        $.post( "", { getData: "generateLink", "token-exp-days":  $('#linkExp').val()}, function( data ) {

            setTimeout(function() {
                generatedInput.val(data.generatedLink).removeClass('hide-text');
            }, 1000);

        }, "json");

    }

    function copyLink(){
        /* Get the text field */
        var copyText = document.getElementById("generatedLink");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        // alert("Copied the text: " + copyText.value);
    }

    function getThumb(filepath, readonly){
        $.post( "", { req: "getThumbnail", imagePath: filepath }, function( data ) {

            var fileName = filepath;


            response = JSON.parse(data);
            var file = "<div id='f-"+ fileName.replace('.', '-') +"' class='col-4'><a href='#' class='show-pdf-modal' data-file='"+ filepath +"'><img title='"+ fileName +"' style='width: 100%; height: auto; border: 1px solid black;' src='" + response + "'></a>";

            if(!readonly) {
                file += "<span class='close delete-file' data-file='"+ filepath +"' data-href='#' data-toggle='modal' data-target='#confirm-delete-file'>x</span>";
            }
            file += "</div>";

            $('#files-list').append(file);

        }, "text");
    }

    <?php
            if($_POST['details']) {

            }
    ?>


    var periodsData = {
        "periods": <?php echo json_encode($_POST['periods']); ?>,
        "details": <?php echo ($_POST['details']) ? "true" : "false"; ?>
    };

    let user = "<?php echo $user; ?>";

    var readonly = <?php echo (readonly()) ? "true" : "false"; ?>;


    var periodsFormOptionsFields = {
        "periods_string": {
            "type": "hidden",
            "id": "periods-string"
        },
        "periods": {
            'fieldClass': 'AAA-periods',
            "toolbarSticky": true,
            "actionbarStyle": "bottom",
            "items": {
                // "readonly": true,
                'fieldClass': 'AAA-items',
                "fields": {
                    "begin": {
                        'fieldClass': 'AAA-begin',
                        "label": "Begin",
                    },
                    "end": {
                        "label": "End",

                    }
                }
            },
            "hideToolbarWithChildren": false,
            "actionbar": {
                "actions": [{
                    "action": "add",
                    "enabled": false
                },{
                    "action": "up",
                    "enabled": false
                },{
                    "action": "down",
                    "enabled": false
                }]
            },
            "toolbarPosition": "bottom",
            "toolbar": {
                "actions": [{
                    "action": "add",
                    "label": 'Add Period',
                }]
            }
        },
        "details" : {
            "rightLabel": 'Details',
            "type": "checkbox",
            "id" : "details-container",
        }
    };

    var periodsFormOptionsFields_readonly = {
        "periods_string": {
            "type": "hidden",
            "id": "periods-string"
        },
        "periods": {
            'fieldClass': 'AAA-periods',
            "toolbarSticky": true,
            "actionbarStyle": "bottom",
            "items": {
                "readonly": true,
                'fieldClass': 'AAA-items',
                "fields": {
                    "begin": {
                        "label": "Begin",
                    },
                    "end": {
                        "label": "End",
                    }
                }
            },
            "hideToolbarWithChildren": true,
            "actionbar": {
                "actions": [{
                    "action": "add",
                    "enabled": false
                },{
                    "action": "up",
                    "enabled": false
                },{
                    "action": "down",
                    "enabled": false
                },{
                    "action": "remove",
                    "enabled": false
                }]
            },
            // "toolbarPosition": "bottom",
            // "toolbar": {
            //     "actions": [{
            //         "action": "add",
            //         "label": 'Add Period',
            //     }]
            // }
        },
        "details" : {
            "rightLabel": 'Details',
            "type": "checkbox",
            "id" : "details-container",
        }
    };

    $(function () {

        $("#periods-form").alpaca({
            "data": periodsData,
            "options": {
                // "label": "Periods",
                // "fields": periodsFormOptionsFields,
                "fields": (readonly) ? periodsFormOptionsFields_readonly : periodsFormOptionsFields ,
                "form": {
                    "attributes": {
                        "method": "post",
                        "action": "",
                        // "enctype": "multipart/form-data",
                        "id": "periods-form"
                    },
                        "buttons": {
                            "submit": {
                                "title": "Opdater",
                                "class": "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA",
                                "click": function (e) {

                                    var value = this.getValue();
                                    var data = {};
                                    var form_data = new FormData();

                                    data['periods_form'] = value;

                                    appendFormdata(form_data, value);

                                    var obj = {};
                                    // var formData = new FormData(form);
                                    for (var key of form_data.keys()) {
                                        obj[key] = form_data.get(key);
                                    }

                                    $('.AAA-items .alpaca-container-item-first label span').each(function (index) {

                                        let label = $(this).html();

                                        if($(label).is('input')){
                                            label = $(label).val();
                                        }
                                        obj['periods['+ index +'][2]'] = label;
                                    });

                                    var params= new URLSearchParams(obj);

                                    $('#periods-string').val(params.toString());

                                   this.form.submit();
                                }
                            },
                            'logout' : {
                                "title": "Logout",
                                "click": function (e) {
                                    location.href='<?php echo $directoryURI; ?>?logout=1';
                                }
                            }
                        }
                }
            },
            "schema": {
                "type": "object",
                "properties": {
                    "periods_string": {
                      "type" : "string"
                    },
                    "periods": {
                        "type": "array",
                        "items": {
                            "type": "object",
                            "properties": {
                                0: {
                                    "title": "Begin",
                                    "type": "string",
                                    "format": "text"
                                },
                                1: {
                                    "title": "End",
                                    "type": "string",
                                    "format": "text"
                                }
                            }
                        },
                        "minItems": 1,
                        "maxItems": 5
                    },
                    "details" : {
                    }
                }
            },
            "postRender": function(control) {

                $("#periods-form .alpaca-form-buttons-container button").removeClass('btn-default').addClass('btn-dark');
                updateLabels();

                var periodInputs = $("#periods-form input:not(:hidden)");
                periodInputs.on("keydown", function(event) {
                    if (event.key === "Enter") {
                        event.preventDefault();
                    }
                });

                $('.alpaca-array-toolbar button, .alpaca-array-actionbar button').on('click', function (e) {
                    onAddRemovePeriodClick(control);
                });

                var $form = $('#periods-form form');

                $form.on('submit', function (e) {
                });

                $('.AAA-items label:not(input)').dblclick(function (e) {
                    if($(this).find('input').length || readonly){
                        return false;
                    }
                    onDoubleClick(e, $(this).find('span')[0]);
                });

            }
        });

        function updateVal(currentEle, value) {

            // $(currentEle).html('<input class="thVal" maxlength="5" type="text" value="' + value + '" />');
            $(currentEle).html('<input class="thVal form-control" type="text" value="' + value + '" />').on("keydown", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                }
            });
            $(".thVal", currentEle).focus().select().keyup(function (event) {
                if (event.keyCode == 13) {
                   // $(currentEle).html($(".thVal").val().trim());
                    saveValue();
                }
            }).click(function(e) {
                e.stopPropagation();
            });



            $(document).click(function() {
                saveValue();
                // $(".thVal").replaceWith(function() {
                //     return this.value;
                // });
            });
        }

        function saveValue(){

            if($(".thVal").length){

                let name = $(".thVal").val();
                let index = $(".thVal").parents('.alpaca-container-item').parents('.alpaca-container-item').data("alpaca-container-item-index");

                let settings = readCookie(user);


                settings.key_html_periods[index][2] = name;
                createCookie(user, JSON.stringify(settings));
                index++;



                $('.table-main').each(function () {
                    $(this).find('tbody tr:first-child td:eq('+ index +') p').html(name);
                });

                // $('.table-main tbody tr:first-child td:eq('+ index +') p').html(name);

                $(".thVal").parents('.AAA-items').find('label span').html(name);


                if ( window.history.replaceState ) {
                    window.history.replaceState( null, null, window.location.href );
                }

            }


        }

        function onDoubleClick(e, el) {
            e.stopPropagation();
            var value = $(el).html();
            updateVal(el, value);
        }

        function updateLabels() {
            $('.AAA-periods > div > .alpaca-container-item').each(function (index) {

                $(this).find('label').each(function () {
                    let currentTitle = $(this).find('span').html();

                    let savedTitle = periodsData.periods?.[index]?.[2];
                    if(savedTitle === undefined)
                        savedTitle = "P" + (index + 1);


                    if(currentTitle === undefined){
                        $(this).find('span').remove();
                        var period = "<span>" + savedTitle + "</span> " + $(this).html();
                        $(this).html(period);
                    }
                });



            });
        }

        function onAddRemovePeriodClick(control) {
            setTimeout(function(){

                updateLabels();
                var periodInputs = $("#periods-form input:not(:hidden)");

                periodInputs.each(function (index) {
                    if(!$(this).val()){
                        $(this).val($(periodInputs[index-2]).val());
                    }
                });

                periodInputs.on("keydown", function(event) {
                    if (event.key === "Enter") {
                        event.preventDefault();
                    }
                });

                $('.alpaca-array-toolbar button, .alpaca-array-actionbar button').on('click', function (e) {
                    onAddRemovePeriodClick(control);
                });

                }, 200);
        }






        $('#newTransactionModal').on('show.bs.modal', function (e) {

            $("#newTransactionForm").alpaca({
                "schema": formSchema,
                "data": {
                    "info": {
                        "UID": "",
                        "Filename": "",
                    },
                    'Transactions': [
                        {'Account': '', 'Amount': 0},
                        {'Account': '', 'Amount': 0}
                    ]
                },
                "options": {
                    "fields": formOptions_fields,
                    "form": {
                        // "toggleSubmitValidState": false, //validate on click
                        "attributes": {
                            "method": "post",
                            "action": "",
                            // "target": "uploadTarget",
                            // "enctype": "multipart/form-data",
                            "id": "edit-form-horizontal"
                        },
                        "buttons": {
                            "submit": {
                                "title": "Save",
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

                                    form_data.set("req", "fileForm-create");

                                    var config = {
                                        // url: "/upload_avatar",
                                        dataType: 'script',
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        data: form_data,                         // Setting the data attribute of ajax with file_data
                                        type: 'post'
                                    };

                                    var promise = this.ajaxSubmit(config);
                                    promise.done(function (d) {

                                        d = JSON.parse(d);

                                        $('#newTransactionModal').modal('hide');


                                        if(d.status == 'success') {
                                            var message = "File: " + d.fileName + " saved!";
                                            message += " in path: " + d.fullPath;
                                            showMessage('alert-success', message);
                                        } else {
                                            showMessage('alert-danger', "File not saved!");
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
                        }
                    }
                },
                "view": formView,
                "postRender": function (control) {

                    var formContainer = $(control.domEl).find('form >.container');

                    var today = new Date();
                    var dd = String(today.getDate()).padStart(2, '0');
                    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = today.getFullYear();

                    today = yyyy+'-'+mm+'-'+dd;

                    uid = Math.random().toString(36).substr(2, 9);

                    control.childrenByPropertyId["info"].childrenByPropertyId["UID"].setValue(uid);
                    control.childrenByPropertyId["info"].childrenByPropertyId["Date"].setValue(today);
                    $(".amount input").val(0);

                    var transactionsLegend = $(".transactions-array legend");
                    var transactionCheck = $("<span></span>");
                    transactionsLegend.append(transactionCheck);
                    updateTransactionsAmount(1);

                    updateFileName(uid);

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

        //right click generate link
        $( ".modal-link-header, .modal-link" ).contextmenu(function() {

            var pageUrl = $(this).data('url');
            $.post( "", { getData: "generateLink", 'pageUrl': pageUrl }, function( data ) {
                $('#generateLinkModal').modal('show').find('#generatedLink').val(data.generatedLink);
            }, "json");

            return false;
        });

    });




</script>

<script type="text/x-handlebars-template" id="template5">
    <div class="container">
        <div class="row">
            <div class="col-sm" id="left">
            </div>
            <div class="col-sm" id="right">
            </div>
        </div>
    </div>
</script>

<script type="text/x-handlebars-template" id="template6">
    <div class="container">
        <div class="row">
            <div class="col-sm" id="top">
            </div>

        </div>
        <div class="row">
            <div class="col-sm" id="bottom">
            </div>

        </div>
    </div>
</script>


<style>

    .bootstrap-lw  {font-size: 16px!important;}

    .bootstrap-lw .opened-lock-icon, .bootstrap-lw .closed-lock-icon {
        display: none;
        height: 16px;
        fill: gray;
        position: absolute;
        top: 50%;
        margin-top: -8px;
        right: 25px;
    }
    .bootstrap-lw .closed-lock-icon {
        fill: white;
    }
    .bootstrap-lw table:not(.advSort) .up .opened-lock-icon, .bootstrap-lw table:not(.advSort) .down .opened-lock-icon{
        display: block;
    }
    .bootstrap-lw .blocked .closed-lock-icon{
        display: block;
    }
    .bootstrap-lw .blocked .opened-lock-icon{
        display: none;
    }

    #newTransactionForm, #editTransactionForm {max-width: 794px; margin: 0 auto;}

    #edit-form-horizontal .alpaca-container .alpaca-container-item { margin-top: 0; }
    #edit-form-horizontal .form-group {display: flex; flex-wrap: wrap;}
    #edit-form-horizontal .transactions-container span.alpaca-required-indicator{
        padding-left: 0;
        display: block;
    }

    #edit-form-horizontal .transactions-array legend span {
        font-size: 20px;
        float: right;
        margin-top: 4px;
    }

    #edit-form-horizontal .transactions-container .alpaca-container {display: flex; flex-wrap: wrap;justify-content: space-between;}
    #edit-form-horizontal .transactions-container .alpaca-container .alpaca-container-item { flex: 2; }
    #edit-form-horizontal .transactions-container .alpaca-container .alpaca-container-item:first-child { flex: 5; }
    #edit-form-horizontal .transactions-container .alpaca-container .alpaca-container-item:nth-child(3) { flex: 1; }
    #edit-form-horizontal .transactions-container >.alpaca-container .alpaca-container-item .form-group { display: block; }
    #edit-form-horizontal .transactions-container .alpaca-container .alpaca-container-item .form-group >div { padding-left: 0; max-width: 100%; }


    /*p-fields*/
    #edit-form-horizontal .transactions-container .alpaca-container .alpaca-container-item.alpaca-container-item-last { width: 100%; flex: auto}
    #edit-form-horizontal .transactions-container  .p-fields-container { border: none; padding: 0;}
    #edit-form-horizontal .transactions-container .p-fields-container .alpaca-container-item:first-child {width: 100%; flex: auto }
    #edit-form-horizontal .transactions-container .p-fields-container .alpaca-container-item.alpaca-container-item-last { flex: 2}
    #edit-form-horizontal .transactions-container .p-fields-container .alpaca-container-item .form-group { display: flex; }

    #edit-form-horizontal .transactions-container .alpaca-container .alpaca-field-currency label,
    #edit-form-horizontal .transactions-container .alpaca-container .alpaca-field-text label {padding: 0;}

    #edit-form-horizontal .form-group input {
        height: 30px;
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
    #edit-form-horizontal .form-group.show-p-fields input {
        height: auto;
    }
    #edit-form-horizontal .form-group .help-block {
        margin-left: 27%;
    }

    .bootstrap-lw .p-fields-container .alpaca-container-item:not(:first-child){
        margin-top: 0;
    }
    .bootstrap-lw .p-fields-container .form-group.show-p-fields{
        margin-bottom: 0;
    }

    .bootstrap-lw .info-container .alpaca-container {
        display: flex;
        flex-wrap: wrap;
    }
    .bootstrap-lw .info-container .alpaca-container .alpaca-container-item {
        flex: 50%;
    }

    /*file upload*/

    .bootstrap-lw .upload-box {
        width: 480px;
        padding: 20px;
        position: relative;
        color: #fff;
        background-color: #343a40;
        margin: 0 auto;
    }

    .bootstrap-lw .box__input {
        text-align: center;
        padding: 1em;
    }

    .bootstrap-lw .box__input label {
        margin-bottom: 1em;
    }

    .bootstrap-lw .box__uploading {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .bootstrap-lw .box__dragndrop,
    .bootstrap-lw .box__uploading,
    .bootstrap-lw .box__success,
    .bootstrap-lw .box__error {
        display: none;
    }

    .bootstrap-lw .upload-box.has-advanced-upload {
        outline: 2px dashed white;
        outline-offset: -10px;
    }

    .bootstrap-lw .upload-box.has-advanced-upload .box__dragndrop {
        display: inline;
    }

    .bootstrap-lw .upload-box.is-dragover {
        background-color: grey;
    }

    .bootstrap-lw .upload-box.is-uploading .box__input {
        visibility: hidden;
    }

    .bootstrap-lw .upload-box.is-uploading .box__uploading {
        display: block;
    }

    .bootstrap-lw .close.delete-file {
        /*color: #fff;*/
        text-shadow: none;
        cursor: pointer;
        font-size: 1rem;
        margin: 0 5px;
        position: absolute;
    }

    .bootstrap-lw .files-list {
        border: 1px solid #eee;
        border-radius: 4px;
        padding: 10px;
        margin: 5px 0px !important;
    }

    .bootstrap-lw .files-list label {
        margin-bottom: 0;
    }


    .bootstrap-lw .files-list >div {
        margin-bottom: 10px;
    }

    #generateLinkModal .modal-body .row+.row{
        margin-top: 1rem;
    }

    #generateLinkModal .modal-body .col{
        display: flex;
        justify-content: center;
    }
    #generateLinkModal .modal-body input#generatedLink{
        flex-grow: 1;
        transition: all 0.5s ease;
        margin-right: 5px;
    }

    #generateLinkModal .modal-body input#generatedLink.hide-text{
        color: rgba(0, 0, 0, 0);
    }

    #generateLinkModal .modal-body input#linkExp{
        padding: 0 8px;
        width: 55px;
        flex-grow: unset;
        text-align: center;
        margin: 0 10px;
    }

    #generateLinkModal .modal-body .exp-time button{
        margin-left: 10px;
    }



    .bootstrap-lw .account-field-with-dropdown .tt-dropdown-menu .tt-suggestion:hover{
        /*background-color: red;*/
    }

    #periods-form {
        margin-bottom: 15px;
        min-width: 200px;
    }
    #periods-form .btn {
        color: #ffffff;
    }

    #periods-form .alpaca-field-array {
        padding: 0;
        border: none;
    }

    #periods-form label input {
        color: #333333;
    }

    #periods-form .alpaca-field-array >div >.alpaca-container-item{
        padding: 0;
        border: 1px solid #ffffff;
        border-radius: 4px;
    }

    #periods-form .alpaca-field-array .alpaca-field-object {
        /*padding: 0;*/
        border: none;
    }
    #periods-form .alpaca-array-actionbar{
        padding: 0 0 10px 0;
        margin-top: -10px;
    }

    #periods-form .alpaca-form-buttons-container{
        margin-top: 0px;
        display: flex;
        justify-content: space-around;
    }

    #periods-form [data-alpaca-field-id="details-container"] {
        margin-bottom: 0;
    }

</style>


<style>
    /* typeahead */
    /* ----------- */

    .bootstrap-lw .tt-menu,
    .bootstrap-lw .gist {
        text-align: left;
    }

    .bootstrap-lw .typeahead,
    .bootstrap-lw .tt-query,
    .bootstrap-lw .tt-hint {
        width: 396px;
        height: 30px;
        padding: 8px 12px;
        font-size: 24px;
        line-height: 30px;
        border: 2px solid #ccc;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
        outline: none;
    }

    .bootstrap-lw .typeahead {
        background-color: #fff;
    }

    .bootstrap-lw .typeahead:focus {
        border: 2px solid #0097cf;
    }

    .bootstrap-lw .tt-query {
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    }

    .bootstrap-lw .tt-hint {
        color: #999
    }

    .bootstrap-lw .tt-menu {
        width: 422px;
        margin: 12px 0;
        padding: 8px 0;
        background-color: #fff;
        border: 1px solid #ccc;
        border: 1px solid rgba(0, 0, 0, 0.2);
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
        -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
        -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
        box-shadow: 0 5px 10px rgba(0,0,0,.2);
    }

    .bootstrap-lw .tt-suggestion {
        padding: 3px 20px;
        font-size: 18px;
        line-height: 24px;
    }

    .bootstrap-lw .tt-suggestion:hover {
        cursor: pointer;
        color: #fff;
        background-color: #0097cf;
    }

    .bootstrap-lw .tt-suggestion.tt-cursor {
        color: #fff;
        background-color: #0097cf;
    }

    .bootstrap-lw .tt-suggestion p {
        margin: 0;
    }

    .bootstrap-lw .gist {
        font-size: 14px;
    }

</style>

<div class="alert alert-success alert-dismissible fade" role="alert" style="position: fixed; top: 5%; left: 50%; transform: translateX(-50%); z-index: 9999;">
    <span class="message"></span>
    <button type="button" class="close" onclick="$(this).parents('.alert').removeClass('show');" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<!-- Generate Link Modal -->
<div class="modal fade" id="generateLinkModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body container" style="text-align: left;">
                    <div class="row exp-time">
                        <div class="col">
                            <label>Expiration time: </label>
                            <input type="text" id="linkExp" value="7"/>
                            <span>days</span>
                            <button onclick="updateLink()">Update</button>
                        </div>
                    </div>
                    <div class="row generated-link">
                        <div class="col">
                            <input type="text" id="generatedLink" />
                            <button onclick="copyLink()">Copy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade fullscreen" id="windowModal" tabindex="-1" role="dialog" aria-labelledby="windowModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div id="iframe-spinner" class="d-flex_ justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hide</button>
                <button id="history-back" type="button" class="btn btn-secondary" disabled>Back</button>
            </div>
        </div>
    </div>
</div>

<!-- Report Comments Modal -->
<div class="modal fade" id="commentsModal" tabindex="-1" role="dialog" aria-labelledby="commentsModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentsModalLabel">Report Comments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="text-align: left;">
                <?php if(!$readonly): ?>
                    <div id="summernote"></div>
                <?php else: ?>
                    <div id="readonly-comments">
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <?php if(!$readonly): ?>
                    <button id="close-commentsModal" type="button" class="btn btn-secondary">Save</button>
                <?php else: ?>
                    <button  type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- New transaction Modal -->
<div class="modal fade fullscreen" id="newTransactionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentsModalLabel">New Transaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="text-align: left;">
                <div id="newTransactionForm" class="alpaca-form-container" name="test"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="text-align: left;">
                <div id="newTransactionForm" class="alpaca-form-container" name="test_1"></div>

            </div>

        </div>
    </div>
</div>

<?php


 function readonly(){
     $readonly = false;
     if($_SESSION["readonly"]){
         $readonly = true;
     }

     return $readonly;
 }

 function runSystemUpdate($tpath){
     $runCommand = false;

     if($GLOBALS['development']){
         $runCommand = false;
     }

     if (!isset($_SESSION['system_command']) && isset($tpath)) {
         $runCommand = true;
     }

//     if (true || $runCommand) {
     if ($runCommand) {
         _log('## run system update ##');

         system("tpath=\"$tpath\" php /svn/svnroot/Applications/key.php ledger bal  >/tmp/bal.foobar");
         $_SESSION['system_command'] = time();
     }
 }
