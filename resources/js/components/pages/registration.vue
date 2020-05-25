<template>
    <div class="registration__page">
        <div class="form__registration">
            <div class="form__head">
                <div class="form__title">Форма регистрации</div>
            </div>
            <div class="form__body">
                <form id="registrationForm" enctype="multipart/form-data">
                    <div class="form-group form-group--custom">
                        <label for="name">Имя<span>*</span></label>
                        <div class="form__wrapper">
                            <i class="fa fa-user-o flex-shrink-0" aria-hidden="true"></i>
                            <input class="input__text"
                                   type="text"
                                   name="name"
                                   id="name"
                            >
                        </div>
                    </div>

                    <div class="form-group form-group--custom">
                        <label for="surname">Фамилия<span>*</span></label>
                        <div class="form__wrapper">
                            <i class="fa fa-gg flex-shrink-0" aria-hidden="true"></i>
                            <input class="input__text"
                                   type="text"
                                   name="surname"
                                   id="surname"
                            >
                        </div>
                    </div>

                    <div class="form-group form-group--custom">
                        <label for="email">Электронная почта<span>*</span></label>
                        <div class="form__wrapper">
                            <i class="fa fa-envelope-open-o flex-shrink-0" aria-hidden="true"></i>
                            <input class="input__text"
                                   type="text"
                                   name="mail"
                                   id="email"
                            >
                        </div>
                    </div>
                    <div class="form-group form-group--custom">
                        <label for="image">Фотография</label>
                        <input class="input__text input__text--custom"
                               type="file"
                               name="image"
                               id="image"
                        >
                    </div>

                    <div class="form-group form-group--custom">
                        <label for="password">Пароль<span>*</span></label>
                        <div class="form__wrapper">
                            <i class="fa fa-key flex-shrink-0" aria-hidden="true"></i>
                            <input class="input__text"
                                   type="password"
                                   name="password"
                                   id="password"
                            >
                        </div>
                    </div>

                    <div class="form-group form-group--custom">
                        <label for="password_confirmation">Введите пароль еще раз<span>*</span></label>
                        <div class="form__wrapper">
                            <i class="fa fa-keyboard-o flex-shrink-0" aria-hidden="true"></i>
                            <input class="input__text"
                                   type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                            >
                        </div>
                    </div>
                    <!--name="submit"-->
                    <div class="form-group d-flex justify-content-between">
                        <input type="button"
                               class="btn__red"
                               value="Зарегистрироваться"
                               @click="register">
                        <router-link to="/sign-in" class="btn__darkBlue">Отмена</router-link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import api from '../../api'
    export default {
        data() {
            return {
            };
        },
        methods: {
            register() {
                const data = new FormData(document.getElementById('registrationForm'));
                const imageFile = document.querySelector('#image');

                // console.log(imageFile.file[0]);
                // // if (imageFile.file[0]) {
                // //     data.append('image', imageFile.files[0]);
                // // }
                api.register(data, (response)=> {});
            }
        },
    }
</script>
