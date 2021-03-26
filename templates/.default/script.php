<?php
/**
 * @global $APPLICATION
 * @global $arResult
 * @global $arParams
 */
?>

<script type="application/javascript">
    `use strict`

    $(() => {
        $(`#form_<?=$arParams['TOKEN']?> *[name="date"]`).datepicker({
            format: `dd.mm.yyyy`,
        })

        $(`#form_<?=$arParams['TOKEN']?> *[name="file"]`).change(e => {
            $(e.currentTarget).next(`label`).html(e.currentTarget.files[0].name)
        })

        $(`#form_<?=$arParams['TOKEN']?> button`).click(() => {
            let validate = true

            $(`#form_<?=$arParams['TOKEN']?> *[required]`).each((index, el) => {
                if ($(el).val() === '') {
                    $(el).css('border', '1px solid red')
                    validate = false
                } else if ($(el).is(':not(:checked)') && $(el).is(':checkbox')) {
                    $(el).parent().css('border', '1px solid red')
                    validate = false
                } else {
                    $(el).css('border', 'unset')
                }
            })

            if (validate) {
                let data = new FormData

                data.append(`TOKEN`, `<?=$arParams['TOKEN']?>`)
                data.append(`DETAIL_URL`, `<?=$APPLICATION->GetCurDir()?>`)
                data.append(`NAME`, $(`#form_<?=$arParams['TOKEN']?> *[name="name"]`).val())
                data.append(`DATE`, $(`#form_<?=$arParams['TOKEN']?> *[name="date"]`).val())
                data.append(`CHECKBOX`, $(`#form_<?=$arParams['TOKEN']?> *[name="checkbox"]`).val())
                data.append(`SELECT`, $(`#form_<?=$arParams['TOKEN']?> *[name="select"]`).val())
                data.append(`PHONE`, $(`#form_<?=$arParams['TOKEN']?> *[name="phone"]`).val())
                data.append(`EMAIL`, $(`#form_<?=$arParams['TOKEN']?> *[name="email"]`).val())
                data.append(`MESSAGE`, $(`#form_<?=$arParams['TOKEN']?> *[name="message"]`).val())
                data.append(`DOCUMENT`, $(`#form_<?=$arParams['TOKEN']?> *[name="file"]`)[0])

                $.ajax({
                    method: `post`,
                    url: `<?=$APPLICATION->GetCurDir()?>`,
                    data: data,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        data = JSON.parse(data)
                        if (data.status === true) {
                            alert(data.message)
                            location.reload()
                        } else {
                            alert(data.message)
                        }
                    }
                })
            }
        })
    })
</script>
