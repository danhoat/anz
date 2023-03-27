import $ from 'jquery';

export const messages = {
    required: '入力必須項目です',
    remote: 'このフィールドを修正してください',
    email: '正しいEメールアドレスを入力してください',
    url: '正しい URL を入力してください',
    date: '有効な日付を入力してください',
    dateISO: '有効な日付を入力してください',
    number: '有効な数字を入力してください',
    digits: '数字のみを入力してください',
    creditcard: '有効なクレジットカード番号を入力してください',
    equalTo: '同じ値をもう一度入力してください',
    extension: '有効な拡張子を含む値を入力してください',
    maxlength: $.validator.format( '{0} 文字以内で入力してください' ),
    minlength: $.validator.format( '{0} 文字以上で入力してください' ),
    rangelength: $.validator.format( '{0} 文字から {1} 文字までの値を入力してください' ),
    range: $.validator.format( '{0} から {1} までの値を入力してください' ),
    step: $.validator.format( '{0} の倍数を入力してください' ),
    max: $.validator.format( '{0} 以下の値を入力してください' ),
    min: $.validator.format( '{0} 以上の値を入力してください' ),

    tel: '正しい電話番号を入力してください',
    zip: '正しい郵便番号を入力してください',
    address: 'ひらがな・カタカナ・漢字のいずれかで1文字以上入力してください'
}
