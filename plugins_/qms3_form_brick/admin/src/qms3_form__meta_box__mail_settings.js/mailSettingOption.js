export const keys = [
  'tos_str',
  'from',
  'from_name',
  'subject_template',
  'main_text_template',
  'block_filter',
  'post_result_template',
  'after_text_template',
  'signature_template',
];

export function serialize({ thanks, notification, bccNotification }) {
  return keys.map(key => {
    return [
      thanks[key],
      notification[key],
      bccNotification[key],
    ];
  });
}

export function deserialize(rows) {
  const thanks = Object.create(null);
  const notification = Object.create(null);
  const bccNotification = Object.create(null);

  for (let i = 0, len = rows.length; i < len; i++) {
    const key = keys[i];
    const row = rows[i];

    thanks[key] = row[0];
    notification[key] = row[1];
    bccNotification[key] = row[2];
  }

  return { thanks, notification, bccNotification };
}
