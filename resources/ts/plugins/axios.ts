import axios from 'axios'
// console.log('token',localStorage.getItem('accessToken'));

const axiosIns = axios.create({
  // You can add your headers here
  // ================================
  // baseURL: 'https://some-domain.com/api/',
  // timeout: 1000,
  // headers: {'X-Custom-Header': 'foobar'}
  //  headers: { 
  //   "Language": "en",
  //   "Content-Type": "multipart/form-data",
  //   "Authorization": 'Bearer '+ localStorage.getItem('accessToken')
  // }

})

// Add an interceptor for requests
axiosIns.interceptors.request.use(
  config => {
    // Modify the request config
    const token =  localStorage.getItem('accessToken');
    config.headers['Authorization'] = `Bearer ${token}`;
    config.headers['Language'] ='en';
    return config;
  },
  error => {
    // Handle request errors
    return Promise.reject(error);
  }
);

axiosIns.interceptors.response.use(
  response => response,
  error => {
    console.log(`error ${error}`)
      const { config, response: { status } } = error;
      const originalRequest = config;

      if (status === 419) {
          // if (!isRefreshing) {
          //     isRefreshing = true;
          //     axios.post('/refresh-csrf').then(() => {
          //         isRefreshing = false;
          //         const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          //         processQueue(null, token);
          //     }).catch(error => {
          //         isRefreshing = false;
          //         processQueue(error, null);
          //     });
          // }

          return new Promise((resolve, reject) => {
              // failedQueue.push({ resolve, reject });
          }).then(token => {
              originalRequest.headers['X-CSRF-TOKEN'] = token;
              return axiosIns(originalRequest);
          }).catch(error => {
              return Promise.reject(error);
          });
      }

      if(status === 401) {
        
          localStorage.removeItem('userData')
          localStorage.removeItem('userAbilities')        
          // window.location.reload();
          window.location.href = '/login';
      }

      return Promise.reject(error);
  }
);

export default axiosIns
