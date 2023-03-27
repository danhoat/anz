/**
 * @param {object} obj
 * @param {string[]} keys
 * @returns {boolean}
 */
export function hasKeys(obj, keys) {
  return keys.every(key => obj.hasOwnProperty(key));
}

/**
 * zip tow arrays.
 * @param {any[]} arr1
 * @param {any[]} arr2
 * @returns {[any, any][]}
 */
export function zip(arr1, arr2) {
  return arr1.map((v, i) => [v, arr2[i]]);
}
