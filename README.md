# Pass Defender

### RU
Pass Defender __поможет__ Вам сохранить и защитить пароли, но не сделает это за вас!

На данный момент скрипт поддерживает 3 параметра запуска.

1. `php pass_defender.php gen_array` создаст массив случайных символов в файл `array.dat`.
2. `php pass_defender.php add_entry [name] [login]` добавит в файл `denoms.dat` запись с названием и логином.
3. `php pass_defender.php decrypt` соотнесет файл `denoms.dat` и `array.dat`, тем самым сгенериря пароль.

Чтобы Ваши данные не украли либо Вы их не потеряли держите Ваш `array.dat` в секрете и никому не показывайте либо храните его на отдельной флешке. Файл `denoms.dat` так же очень важен, но Вы либо преступники не смогут извлечь из этих файлов пользы по отдельности.
Если вы потеряете любой из этих файлов, то Вы потеряете данные!

Pass Defender работает не по принципу, что пароль генерируете либо придумываете вы сами пароль станет известен только после того, как вы сгенерируете оба файла и введете комманду `php pass_defender.php decrypt`.

***

### EN
Pass Defender will __help__ you save and protect passwords, but will not do it for you!

At the moment, the script supports 3 startup parameters.

1. `php pass_defender.php gen_array` will create an array of random characters in the file `array.dat`.
2. `php pass_defender.php add_entry [name] [login]` will add a record with a name and login to the `denoms.dat`.
3. `php pass_defender.php decrypt` will correlate the file `denoms.dat` and `array.dat`, thereby generating a password.

So that your data is not stolen or you have not lost it, keep your `array.dat` in secret and do not show it to anyone or store it on a separate flash drive. File `denoms.dat` is also very important, but you or criminals can not extract from these files the benefits separately. If you lose any of these files, you will lose data!

Pass Defender does not work on the principle that you generate a password, or you come up with the password itself, will be known only after you generate both files and enter the `php pass_defender.php decrypt` command.
