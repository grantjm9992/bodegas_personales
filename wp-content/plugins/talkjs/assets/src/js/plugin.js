
    const $ = jQuery;

	//validator
	class ApiValidator{

		constructor( _fields ){

            this.fields = _fields;
            this.setEvents();

		}

		/**
		 * Set all events for this class
		 */
		setEvents(){

			let self = this;

			for( let i = 0; i < self.fields.length; i++ ){

				let field = $( this.fields[ i ] );
				field.on( 'change', event => {

					event.preventDefault();
					self.validateFields( self );

				});

			}

			self.validateFields( self );
		}



		/**
		 * Validate field function
		 *
		 * @param  ApiValidator self
		 *
		 * @return void
		 */
		validateFields( self ){

            self.fields.parent().find('.validation-errors').remove();

            if( self.fieldsNotEmpty( self ) ){

                self.checkApi( self );

            }else{
                self.fields.parent().removeClass( 'validated' );
                self.fields.parent().removeClass( 'validated-false' );
            }
		}

		/**
		 * Check the api
		 *
		 * @param  ApiValidator self
		 *
		 * @return void
		 */
		checkApi( self ){

            let session = self.createSession( self );
            let isValidPromise = session.hasValidCredentials();

            try{

				isValidPromise.then( isValid => {

                    let _parent = self.fields.parent();

                    if( isValid ){
                        _parent.addClass( 'validated' );
                        _parent.removeClass( 'validated-false' );
                        _parent.addClass( 'validated-true' );
					}else{
                        self.validateFalse( self );
                    }
				});

			} catch( e ){
                self.validateFalse( self );
			}
        }

        /**
         * Validate this field as false
         *
         * @param Object self
         */
        validateFalse( self, error ){

            let _parent = self.fields.parent();
            let _error = error;

            if( typeof( error ) == 'undefined' ){
                error = TalkJS.errors.noValidKey;
			}

            _parent.addClass( 'validated' );
            _parent.removeClass( 'validated-true' );
            _parent.addClass( 'validated-false' );
            self.fields.last().parent().append('<div class="validation-errors">'+error+'</div>');

        }

		/**
		 * Check to see if these fields aren't empty
		 *
		 * @param  ApiValidator self
		 *
		 * @return boolean
		 */
		fieldsNotEmpty( self ){

			let fullLength = self.fields.length;

			//filter for filled-in fields:
			let fields = self.fields.toArray();
			fields = fields.filter( field => $( field ).val() != '' );

			//if the length is the same, both fields are filled in:
			if( fields.length == fullLength )
				return true;

			return false;
        }


        createSession( self ){

            const appId = $('#field-appid-text').val();
            const secretKey = $('#field-secretkey-text').val();

            let user = new Talk.User({ id: userSettings.uid, name: 'test' });
            let session = new Talk.Session({
                appId: appId,
                me: user,
                signature: CryptoJS.HmacSHA256(user.id, secretKey).toString(CryptoJS.enc.Hex)
            });

            return session;
        }
    }


    //user Role validation
    class UserRoleValidator {

        constructor( fields, apiConnection ){
            this.fields = fields;
            this.parent = fields.first().parent().parent();
            this.apiConnection = apiConnection

            this.setEvents();
        }

        /**
		 * Set all events for this class
		 */
		setEvents(){

			let self = this;

			for( let i = 0; i < self.fields.length; i++ ){

                let field = $( this.fields[ i ] );
				field.on( 'change', event => {

                    field.removeClass( 'config-false' );
                    field.siblings('.config-error').remove();

					if( field.is(':checked' ) ){
                        self.validateRole( self, field );
                    }

                    self.validateFields( self );
				});
            }

		}

        /**
         * Validate the role being switched
         *
         * @param Object self
         * @param Object field
         */
        validateRole( self, field ){

            let session = this.apiConnection.createSession( this.apiConnection );

            if( typeof( session.hasConfiguration ) == 'undefined' )
                return true;


            try{

                let _value = field.val();
				session.hasConfiguration( _value ).then( isValid => {

                    let _parent = self.fields.parent();

                    if( !isValid ){

                        field.addClass( 'config-false' );
                        field.parent().append('<p class="config-error">- '+TalkJS.errors.configurationNotAvailable+'</p>' );

                    }else{
                        return true;
                    }
				});

			} catch( e ){
                return true;
            }

            return false;
        }

        /**
         * Validate fields
         *
         * @param Object self
         */
        validateFields( self ){

            let _validated = true;

            for( let i = 0; i < self.fields.length; i++ ){

                let field = $( this.fields[ i ] );
                if( field.hasClass( 'config-false' ) )
                    _validated = false;

            }

            self.parent.find('.validation-errors').remove();

            if( _validated == false ){
                self.parent.append('<div class="validation-errors">'+TalkJS.errors.configurationsNotSet+'</div>');
            }
        }
    }


	// Settings Page
	class SettingsPage {

		constructor(){

            let _fields = $( '.check-connection' );
            if( _fields.length > 0 ) {
				this.apiValidator = new ApiValidator( _fields );
				this.userRoleValidator = new UserRoleValidator( $('.check-configurations'), this.apiValidator );
                this.setEvents()
			}

		}


		setEvents(){

			$('.button-wrapper .button-primary').on( 'click tap', event => {
                event.preventDefault();

                if( $('.settings-page-wrapper .validated-false').length > 0 ){

                    $('.settings-page-wrapper .general-error-msg' ).addClass( 'active' );
                    setTimeout( () => {
                        $('.settings-page-wrapper .general-error-msg').removeClass( 'active' );
                    }, 2000 );

                }else{
                    $('.settings-page-wrapper form').submit();
                }


            });

		}

	}

	Talk.ready.then( () => {
		new SettingsPage();

	});


