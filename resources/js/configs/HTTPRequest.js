import "../bootstrap.js";

export default (config = {})=> {
    return axios.create(config);
}
