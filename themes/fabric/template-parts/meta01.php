<?php
$setting = fabric_load_setting();

if ( empty( $setting[ 'logo' ] ) ) { return; }
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "<?= $setting['logo']; ?>",//会社名
  "URL": "<?= esc_url(home_url() ); ?>/",
  "logo": "<?= esc_url(get_stylesheet_directory_uri() ); ?><?= $setting['logo_img'] ?>",//企業ロゴ
  "image": "<?= esc_url(get_stylesheet_directory_uri() ); ?><?= $setting['search_img'] ?>",//検索画像
  "telephone": "+81<?= $setting['tel_link'] ?>",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "<?= $setting['street_address'] ?>",//番地
    "addressLocality": "<?= $setting['address_locality'] ?>",//市区町村
    "addressRegion": "<?= $setting['address_region'] ?>",//県
    "postalCode": "<?= $setting['postal_code'] ?>",//郵便番号
    "addressCountry": "JP"//国
  },
  "sameAs": [
    "<?= $setting['ig_link'] ?>",//SNS等のURL
    "<?= $setting['yt_link'] ?>",//SNS等のURL
    "<?= $setting['line_link'] ?>"//SNS等のURL（最後はカンマ無し）
  ]
}
</script>
