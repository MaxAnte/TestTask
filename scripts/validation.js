(function () {
  //Две первые проверки позволяют появится всплывающему окну с сообщениями об ошибках и успешной регистрации.
  if ($('.form-wrap .error-box').length > 0) {
    setTimeout(function () {
      $('.form-wrap .error-box').addClass('error__active');
    }, 500);
    setTimeout(function () {
      $('.form-wrap .error-box.error__active').removeClass('error__active');
    }, 3000);
  }

  if ($('.form-wrap .message-box').length > 0) {
    setTimeout(function () {
      $('.form-wrap .message-box').addClass('message__active');
    }, 500);
    setTimeout(function () {
      $('.form-wrap .message-box.message__active').removeClass('message__active');
    }, 3000);
  }

  //Функция валидации формы авторизации.
  $('#logInForm').on('submit', function () {
    let data = {};  //Создается переменная, которая видима только в этой блоке. Кстати, созается объект, в который в следующих двух строчках
                    //записываются панели ввода.
    data.userName = $('input[name="LogIn[userName]"]', this);
    data.password = $('input[name="LogIn[password]"]', this);

    //Проверка на результат валидации. Если функция вернет значение переменной error = 1, то форма не отправится. Функция создается ниже.
    if (validate(data) === 1) {
      return false;
    } else {
      return true;
    }
  });
  
  //Функция валидации формы регистрации. Система аналогичная валидации формы авторизации, но так же добавляется проверка на соответствие паролей.
  $('#signInForm').on('submit', function () {
    let data = {};
    data.firstName = $('input[name="Registration[firstName]"]', this);
    data.lastName = $('input[name="Registration[lastName]"]', this);
    data.userName = $('input[name="Registration[userName]"]', this);
    data.email = $('input[name="Registration[email]"]', this);
    data.password = $('input[name="Registration[password]"]', this);
    data.confirmPassword = $('input[name="Registration[confirmPassword]"]', this);

    if (validate(data) === 1 | validatePasswords(data) === 1) {
      return false;
    } else {
      return true;
    }
  });

  //Функция валидации формы. В функцию передается объект с информацией полей ввода. В цикле проверяется длинна значения поля, если она допустимая, то
  //рамка поля ввода окрасится зеленым цветом, таким образом показывая, что валидация прошла успешно. В обратном случае, рамка окрасится красным и 
  //снизу появится сообщение с ошибкой и функция вернет переменную error со значением "1".
  function validate(obj) {
    let error = 0;
    $.each(obj, function (key, value) {
      if (value.val().length >= 2 && value.val().length <= 25) {
        value.parent().removeClass('error');
        value.parent().addClass('success');
        value.parent().next().text('');
      } else {
        value.parent().removeClass('success');
        value.parent().addClass('error');
        value.parent().next().text('Write more letters!');
        error = 1;
      }
    });
    return error;
  }

  //Функция валидации соответствия паролей в форме регистрации. Опять же передается объект с полями ввода и сверяются их данные.
  function validatePasswords(obj) {
    let error = 0;
    if (obj.password.val() === obj.confirmPassword.val() &&
      obj.password.val().length != 0 && obj.confirmPassword.val().length != 0) {
      obj.password.parent().removeClass('error');
      obj.password.parent().addClass('success');
      obj.password.parent().next().text('');

      obj.confirmPassword.parent().removeClass('error');
      obj.confirmPassword.parent().addClass('success');
      obj.confirmPassword.parent().next().text('');
    } else {
      obj.password.parent().removeClass('success');
      obj.password.parent().addClass('error');
      obj.password.parent().next().text('Passwords did not match!');

      obj.confirmPassword.parent().removeClass('success');
      obj.confirmPassword.parent().addClass('error');
      obj.confirmPassword.parent().next().text('Passwords did not match!');

      error = 1;
    }
    return error;
  }

})(jQuery)