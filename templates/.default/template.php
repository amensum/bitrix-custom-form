<?
/**
 * @var $APPLICATION
 * @var $templateFolder
 * @var $arParams
 * @var $arResult
 */
?>

<script src="<?= $templateFolder ?>/js/jquery-3.4.1.min.js"></script>
<script src="<?= $templateFolder ?>/js/jquery-ui.js"></script>
<script src="<?= $templateFolder ?>/js/jquery.paroller.min.js"></script>
<script src="<?= $templateFolder ?>/js/bootstrap.min.js"></script>
<script src="<?= $templateFolder ?>/js/datepicker/bootstrap-datepicker.min.js"></script>

<link rel="stylesheet" href="<?= $templateFolder ?>/css/jquery-ui.css">
<link rel="stylesheet" href="<?= $templateFolder ?>/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= $templateFolder ?>/css/datepicker/bootstrap-datepicker.standalone.min.css">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-6">
            <form id="form_<?= $arParams['TOKEN'] ?>" enctype="multipart/form-data" type="post">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="name">First name</label>
                            <input class="form-control" type="text" name="name" placeholder="First name"
                                   id="name" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                            <div class="invalid-feedback">
                                Please provide a valid name.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="phone">Phone</label>
                            <input class="form-control" type="tel" placeholder="Phone"
                                   id="phone" name="phone" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                            <div class="invalid-feedback">
                                Please provide a valid phone.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email">E-mail</label>
                            <input class="form-control" type="email" placeholder="E-mail"
                                   id="email" name="email" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                            <div class="invalid-feedback">
                                Please provide a valid e-mail.
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="message">Message</label>
                            <textarea class="form-control" placeholder="Message" name="message" rows="5"
                                      id="message" required></textarea>
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Please provide a valid message.
                        </div>
                    </div>
                    <div class="col-md-12 mb-3 custom-file">
                        <input type="file" class="custom-file-input" id="file" name="file" required>
                        <label class="custom-file-label" for="file">Choose file...</label>
                    </div>
                    <div class="form-row align-items-center">
                        <div class="col-md-4 mb-3 date">
                            <label class="sr-only" for="date">Date</label>
                            <input type="text" class="form-control" placeholder="Date"
                                   id="date" name="date" required>
                            <span class="input-group-addon"></span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="sr-only" for="select">Preference</label>
                            <select class="custom-select" id="select" name="select" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       id="checkbox" name="checkbox" required>
                                <label class="custom-control-label"
                                       for="checkbox">Check</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary" name="button">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if ($arParams['RECAPTCHA_ENABLED'] === 'Y') {
    include('script.recaptcha.php');
} else {
    include('script.php');
}
