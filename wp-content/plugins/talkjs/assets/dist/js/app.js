'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var $ = jQuery;

//validator

var ApiValidator = function () {
            function ApiValidator(_fields) {
                        _classCallCheck(this, ApiValidator);

                        this.fields = _fields;
                        this.setEvents();
            }

            /**
             * Set all events for this class
             */


            _createClass(ApiValidator, [{
                        key: 'setEvents',
                        value: function setEvents() {

                                    var self = this;

                                    for (var i = 0; i < self.fields.length; i++) {

                                                var field = $(this.fields[i]);
                                                field.on('change', function (event) {

                                                            event.preventDefault();
                                                            self.validateFields(self);
                                                });
                                    }

                                    self.validateFields(self);
                        }

                        /**
                         * Validate field function
                         *
                         * @param  ApiValidator self
                         *
                         * @return void
                         */

            }, {
                        key: 'validateFields',
                        value: function validateFields(self) {

                                    self.fields.parent().find('.validation-errors').remove();

                                    if (self.fieldsNotEmpty(self)) {

                                                self.checkApi(self);
                                    } else {
                                                self.fields.parent().removeClass('validated');
                                                self.fields.parent().removeClass('validated-false');
                                    }
                        }

                        /**
                         * Check the api
                         *
                         * @param  ApiValidator self
                         *
                         * @return void
                         */

            }, {
                        key: 'checkApi',
                        value: function checkApi(self) {

                                    var session = self.createSession(self);
                                    var isValidPromise = session.hasValidCredentials();

                                    try {

                                                isValidPromise.then(function (isValid) {

                                                            var _parent = self.fields.parent();

                                                            if (isValid) {
                                                                        _parent.addClass('validated');
                                                                        _parent.removeClass('validated-false');
                                                                        _parent.addClass('validated-true');
                                                            } else {
                                                                        self.validateFalse(self);
                                                            }
                                                });
                                    } catch (e) {
                                                self.validateFalse(self);
                                    }
                        }

                        /**
                         * Validate this field as false
                         *
                         * @param Object self
                         */

            }, {
                        key: 'validateFalse',
                        value: function validateFalse(self, error) {

                                    var _parent = self.fields.parent();
                                    var _error = error;

                                    if (typeof error == 'undefined') {
                                                error = TalkJS.errors.noValidKey;
                                    }

                                    _parent.addClass('validated');
                                    _parent.removeClass('validated-true');
                                    _parent.addClass('validated-false');
                                    self.fields.last().parent().append('<div class="validation-errors">' + error + '</div>');
                        }

                        /**
                         * Check to see if these fields aren't empty
                         *
                         * @param  ApiValidator self
                         *
                         * @return boolean
                         */

            }, {
                        key: 'fieldsNotEmpty',
                        value: function fieldsNotEmpty(self) {

                                    var fullLength = self.fields.length;

                                    //filter for filled-in fields:
                                    var fields = self.fields.toArray();
                                    fields = fields.filter(function (field) {
                                                return $(field).val() != '';
                                    });

                                    //if the length is the same, both fields are filled in:
                                    if (fields.length == fullLength) return true;

                                    return false;
                        }
            }, {
                        key: 'createSession',
                        value: function createSession(self) {

                                    var appId = $('#field-appid-text').val();
                                    var secretKey = $('#field-secretkey-text').val();

                                    var user = new Talk.User({ id: userSettings.uid, name: 'test' });
                                    var session = new Talk.Session({
                                                appId: appId,
                                                me: user,
                                                signature: CryptoJS.HmacSHA256(user.id, secretKey).toString(CryptoJS.enc.Hex)
                                    });

                                    return session;
                        }
            }]);

            return ApiValidator;
}();

//user Role validation


var UserRoleValidator = function () {
            function UserRoleValidator(fields, apiConnection) {
                        _classCallCheck(this, UserRoleValidator);

                        this.fields = fields;
                        this.parent = fields.first().parent().parent();
                        this.apiConnection = apiConnection;

                        this.setEvents();
            }

            /**
            * Set all events for this class
            */


            _createClass(UserRoleValidator, [{
                        key: 'setEvents',
                        value: function setEvents() {
                                    var _this = this;

                                    var self = this;

                                    var _loop = function _loop(i) {

                                                var field = $(_this.fields[i]);
                                                field.on('change', function (event) {

                                                            field.removeClass('config-false');
                                                            field.siblings('.config-error').remove();

                                                            if (field.is(':checked')) {
                                                                        self.validateRole(self, field);
                                                            }

                                                            self.validateFields(self);
                                                });
                                    };

                                    for (var i = 0; i < self.fields.length; i++) {
                                                _loop(i);
                                    }
                        }

                        /**
                         * Validate the role being switched
                         *
                         * @param Object self
                         * @param Object field
                         */

            }, {
                        key: 'validateRole',
                        value: function validateRole(self, field) {

                                    var session = this.apiConnection.createSession(this.apiConnection);

                                    if (typeof session.hasConfiguration == 'undefined') return true;

                                    try {

                                                var _value = field.val();
                                                session.hasConfiguration(_value).then(function (isValid) {

                                                            var _parent = self.fields.parent();

                                                            if (!isValid) {

                                                                        field.addClass('config-false');
                                                                        field.parent().append('<p class="config-error">- ' + TalkJS.errors.configurationNotAvailable + '</p>');
                                                            } else {
                                                                        return true;
                                                            }
                                                });
                                    } catch (e) {
                                                return true;
                                    }

                                    return false;
                        }

                        /**
                         * Validate fields
                         *
                         * @param Object self
                         */

            }, {
                        key: 'validateFields',
                        value: function validateFields(self) {

                                    var _validated = true;

                                    for (var i = 0; i < self.fields.length; i++) {

                                                var _field = $(this.fields[i]);
                                                if (_field.hasClass('config-false')) _validated = false;
                                    }

                                    self.parent.find('.validation-errors').remove();

                                    if (_validated == false) {
                                                self.parent.append('<div class="validation-errors">' + TalkJS.errors.configurationsNotSet + '</div>');
                                    }
                        }
            }]);

            return UserRoleValidator;
}();

// Settings Page


var SettingsPage = function () {
            function SettingsPage() {
                        _classCallCheck(this, SettingsPage);

                        var _fields = $('.check-connection');
                        if (_fields.length > 0) {
                                    this.apiValidator = new ApiValidator(_fields);
                                    this.userRoleValidator = new UserRoleValidator($('.check-configurations'), this.apiValidator);
                                    this.setEvents();
                        }
            }

            _createClass(SettingsPage, [{
                        key: 'setEvents',
                        value: function setEvents() {

                                    $('.button-wrapper .button-primary').on('click tap', function (event) {
                                                event.preventDefault();

                                                if ($('.settings-page-wrapper .validated-false').length > 0) {

                                                            $('.settings-page-wrapper .general-error-msg').addClass('active');
                                                            setTimeout(function () {
                                                                        $('.settings-page-wrapper .general-error-msg').removeClass('active');
                                                            }, 2000);
                                                } else {
                                                            $('.settings-page-wrapper form').submit();
                                                }
                                    });
                        }
            }]);

            return SettingsPage;
}();

Talk.ready.then(function () {
            new SettingsPage();
});
//# sourceMappingURL=app.js.map
