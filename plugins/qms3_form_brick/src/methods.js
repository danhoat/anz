/**
 * 電話番号のバリデーションロジック
 *
 * @param {string} value
 * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} elem
 * @returns {boolean}
 */
export function is_email(value, elem) {
  if (this.optional(elem)) { return true }

  return /^[a-zA-Z0-9!$&*.=^`|~#%'+\/?_{}-]+@([a-zA-Z0-9_-]+\.)+[a-zA-Z]{2,}$/.test(value.trim())
}

/**
 * 電話番号のバリデーションロジック
 *
 * @param {string} value
 * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} elem
 * @returns {boolean}
 */
export function is_tel(value, elem) {
    if (this.optional(elem)) { return true }

    value = value.trim().replace(/[０-９]/g, function(s) {
      return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);  // 全角数字を半角数字に変換
    });

    return /^0(\d{1}[-(]?\d{4}|\d{2}[-(]?\d{3}|\d{3}[-(]?\d{2}|\d{4}[-(]?\d{1})[-)]?\d{4}$/.test(value)
        || /^\d{1,4}\-?\d{4}$/.test(value)
        || /^0[5789]0[-(]?\d{4}[-)]?\d{4}$/.test(value)
        || /^0120[-(]?\d{3}[-)]?\d{3}$/.test(value)
}

/**
 * 郵便番号のバリデーションロジック
 *
 * @param {string} value
 * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} elem
 * @returns {boolean}
 */
export function is_zip(value, elem) {
    if (this.optional(elem)) { return true }

    value = value.trim()
      .replace(/[０-９]/g, function(s) {
        return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);  // 全角数字を半角数字に変換
      })
      .replace(/[^\d]/g, '');

    return /^\d{7}$/.test(value);
}

/**
 * 住所のバリデーションロジック
 *
 * @param {string} value
 * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} elem
 * @returns {boolean}
 */
export function is_address(value, elem) {
  if (this.optional(elem)) { return true }

  return /[^\x00-\x7e]/.test(value.trim());
}

/**
 * @param {any} value
 * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} elem
 * @param {[number,string]} options
 */
export function require_from_group(value, elem, options) {
  const [num_min_completed, selector] = options;

  const $fields = $(selector, elem.form);
  const $fieldsFirst = $fields.eq(0);
  const validator = $fieldsFirst.data('valid_req_grp') ? $fieldsFirst.data('valid_req_grp') : $.extend({}, this);

  return $fields.filter(function() {
    return validator.elementValue(this);
  }).length >= num_min_completed;
}

/**
 * @param {any} value
 * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} elem
 * @param {string|RegExp} pattern
 */
export function match_pattern(value, elem, pattern) {
  if (this.optional(elem)) { return true }

  if (typeof pattern === 'string') {
    pattern = new RegExp(`^(?:${pattern})$`);
  }

  return pattern.test(value);
}
