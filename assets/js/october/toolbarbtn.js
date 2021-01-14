function handlePageBuilderToolBtn() {
        $.oc.stripeLoadIndicator.show();

        const formlayout = $('form.layout');
        sessionStorage.setItem('_session_key', formlayout.find('input[name="_session_key"]').val());
        sessionStorage.setItem('_token', formlayout.find('input[name="_token"]').val());
        sessionStorage.setItem('objectPath', formlayout.find('input[name="objectPath"]').val());
        sessionStorage.setItem('formWidgetAlias', formlayout.find('input[name="formWidgetAlias"]').val());
        sessionStorage.setItem('pagereferrer', 'rainlab.pages');

        var pageuri = formlayout.find('input[name="viewBag[url]"]').val();
        var uriarr = window.location.href.split('/')
        var backenduriseg = typeof uriarr[3] !== 'undefined' ? uriarr[3] : 'backend';

        $.oc.stripeLoadIndicator.show();
        pageuri = pageuri.replace('/', '');
        var pburl;
        if (pageuri !== '') {
            pburl = '/' + backenduriseg + '/mg/pagebuilder?page=' + pageuri;
        } else {
            pburl = '/' + backenduriseg + '/mg/pagebuilder';
        }
        window.location.href = pburl;
}
//Add Builder Button after first save of a new page
function initAddPageBuilderBtn() {
    typeof addBuilderRainlabPage !== 'undefined' && addBuilderRainlabPage && addBuilderBtnAfterFirstSave();
}
function addBuilderBtnAfterFirstSave() {
    (function() {
        //use selector for active tab save button for click event
        $('.tab-content.layout-row div.tab-pane.active form.layout .form-tabless-fields a.btn-primary.save').on('click', function (e) {
            if ($('#pages-master-tabs form').hasClass('oc-data-changed')) {
                var validinput = true;
                $('.form-tabless-fields input').each(function () {
                    if ($(this).val() === '') {
                        validinput = false;
                    }
                    if ($(this).attr('id').indexOf('field-viewBag-url') !== -1) {
                        if ($(this).val().indexOf('/') === -1) {
                            validinput = false;
                        }
                    }
                });
                if (validinput) {
                    setTimeout(function () {
                        $('#page-builder').removeClass('hide');
                    }, 5000)
                }
            }
        });
        addBuilderRainlabPage = false;
        initAddPageBuilderBtn();
    })();
}
initAddPageBuilderBtn();
