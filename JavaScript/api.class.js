class api {
    constructor() {
        this.api_url = null;
    }

    /**
     * Set the URL for the API.
     *
     * @param {string} url - The URL to set for the API
     * @return {void} 
     */
    setURL = (url) => {
        this.api_url = url;
    }
    getURL = _=> {return this.api_url}
  
    /**
     * A function that calls an API with the specified method and form parameters, 
     * and executes a callback function with the received data.
     *
     * @param {string} method - The HTTP method to use for the API call.
     * @param {object} formParameter - The parameters to send in the API call.
     * @param {function} callback - The callback function to execute with the received data.
     * @return {Promise} A Promise that resolves after the API call is completed.
     */
    call_api = (method, formParameter, callback) => {
        let url = this.api_url;
        const formData = new FormData();

        for(const element in formParameter){
            formData.append(element, formParameter[element]);
        }

        return new Promise((resolve) => {
            fetch(url, {
                method: method,
                body: formData
            })
            .then(response => {
                if (!response.ok){
                    throw new Error('Error fetching data.');
                }
                const contentType = response.headers.get('Content-Type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Response is not in JSON format');
                }
                return response.json();
            })
            .then(data => {
                callback(data);
            })
            .catch(error => {
                console.error(error)
                console.error('Error: ', JSON.parse(error));
                resolve(false);
            })

        })
        .then(_=>{});
    };

    /**
     * Fetches the status of the API using the provided method and callback function.
     *
     * @param {string} method - The HTTP method to use for the API request
     * @param {function} callback - The callback function to handle the API response
     * @return {Promise} A Promise that resolves when the API status is fetched
     */
    get_api_status = (method, callback) => {
        return new Promise((resolve) => {
            fetch(this.api_url, {
                    method: method
                  })
            .then(
                response => {
                    callback(response.url, response.status);
                }
            ).catch(err =>{})
        }).then();
    }
}
