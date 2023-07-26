let API_BASE_URL = '';

if(window.location.href.includes('localhost')){
    API_BASE_URL = 'http://localhost:8000/api/v1' ;
}
// else if( window.location.href.includes('stage')){
//     VUE_APP_BASE_URL = 'https://stagingapi.com/api/v1' ;
// }
// else if(window.location.href.includes('prod')){
//     VUE_APP_BASE_URL = 'https://productionapi.com/api/v1' ;
// }

export default API_BASE_URL ;