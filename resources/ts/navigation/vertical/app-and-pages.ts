export default [
  // { heading: 'Apps & Pages' },
  // {
  //   title: 'Email',
  //   icon: { icon: 'tabler-mail' },
  //   to: 'apps-email',
  // },

  {
    title: 'content_management',
    icon: { icon: 'tabler-message' },
    to: 'content-management-list',
  },
  {
    title: 'user_management',
    icon: { icon: 'tabler-users-plus' },
    children: [
      
      { title: 'admin_management', to: 'admin-management-list',  icon: { icon: 'tabler-user-bolt', size:18 }},
      { title: 'client_management', to: 'client-management-list', icon: { icon: 'tabler-user-pin', size:18 } },
      { title: 'role_and_permission',  to: 'roles-permission-list', icon: { icon: 'tabler-user-check', size:18 } },
    ],
  },
 
  {
    title: 'masters',
    icon: { icon: 'tabler-apps-filled' },
    children: [
      {
        title: 'content-advisory',
        icon : {icon: 'tabler-brand-tripadvisor', size:18 } ,
        to: 'masters-content-advisory' ,
        
      },
      {
        title: 'platform_management',
        icon : {icon: 'tabler-brand-bilibili', size:18 } ,
        to: 'masters-platform-management',
      },
      {
        title: 'language_management',
        icon : {icon: 'tabler-language', size:18 } ,
        to: 'masters-language-management',
      },
      {
        title: 'city_management',
        icon : {icon: 'tabler-bus-stop', size:18 } ,
        to: 'masters-city-management',
      },
      {
        title: 'state_management',
        icon : {icon: 'tabler-building-estate', size:18 } ,
        to: 'masters-state-management',
      },
      {
        title: 'country_management',
        icon : {icon: 'tabler-world', size:18 } ,
        to: 'masters-country-management',
      },
      {
        title: 'module_management',
        icon : {icon: 'tabler-hexagons', size:18 } ,
        to: 'masters-module-management',
      },
      {
        title: 'ads_management',
        icon : {icon: 'tabler-ad', size:18 } ,
        to: 'masters-ads-management',
      },
      {
        title: 'analytics_management',
        icon : {icon: 'tabler-brand-google-analytics', size:18 } ,
        to: 'masters-analytics-management',
      },
      {
        title: 'content_availability',
        to: 'masters-content-availability',
      },
      {
        title: 'broad_caster',
        to: 'masters-broad-caster',
      },
      {
        title: 'genre',
        to: 'masters-genere-management',
      },
      {
        title: 'viewer_rating',
        to: 'masters-viewer-rating',
      },
      {
        title: 'date_format',
        to: 'masters-date-formats',
      },
      {
        title: 'asset_type',
        to: 'masters-asset-type',
      },
      {
        title: 'bitframe',
        to: 'masters-bitframe',
      },
      {
        title: 'payment_mode',
        to: 'masters-payment-mode',
      }
    ],
  },

  {
    title: 'video_library',
    icon: { icon: 'tabler-brand-youtube' },
    to: 'video-library-list',
  },
  {
    title: 'audio_library',
    icon: { icon: 'tabler-music' },
    to: 'media-audio-library',
  },
  {
    title: 'app_management',
    icon: { icon: 'tabler-apps-filled' },
    to: 'app-management-list',
  },
  {
    title: 'module_management',
    icon: { icon: 'tabler-brand-asana' },
    to: 'module-management-list',
  },
  
  {
    title: 'monetization',
    icon: { icon: 'tabler-coin-rupee' },
    children: [
      {
        title: 'pack_management',
        children: [
          {
             title: 'subscription_plan', to: 'monetization-subscription-plan' ,
          },
          {
            title: 'rental_plan', to: 'monetization-rental-plan' ,
         },
          {
            title: 'free_plan',
            to: 'monetization-free-plan'
          },

        ],
      },
      { title: 'discount_code', to: 'monetization-discount-code' },
      // { title: 'Activation Code', to: { name: 'pages-user-profile-tab', params: { tab: 'profile' } } },
      { title: 'activation_code', to: 'monetization-activation-code' },
      { title: 'groups', to: 'monetization-groups' },
     
    ],
  },
  {
    title: 'user_engagement',
    to: 'user-user-engagement',
    icon: { icon: 'tabler-user-cog' },
  },
  {
    title: 'user_support',
    to: 'user-user-support',
    icon: { icon: 'tabler-24-hours' },
  },
 
  {
    title: 'settings',
    to: 'user-settings',
    icon: { icon: 'tabler-settings' },
  },

  {
    title: 'notification',
    icon: { icon: 'tabler-info-circle' },
    children: [
      { title: 'email', to: 'notification-email-list', icon: { icon: 'tabler-mail', size:18 }, },
      { title: 'sms', to: 'notification-sms-list', icon: { icon: 'tabler-message', size:18 }, },
      // { title: 'whatsapp', to: 'notification-whatsapp',icon: { icon: 'tabler-brand-whatsapp', size:18 }, },
     
    ],
  },
]
