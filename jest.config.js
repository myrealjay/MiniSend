module.exports = {
    moduleFileExtensions: [
        "js",
        "json",
        "vue"
    ],
    transform: {
        "^.+\\.js$": "babel-jest",
        ".*\\.(vue)$": "vue-jest"
    },
    transformIgnorePatterns: ['/node_modules\/(?!my-package)(.*)'],
    moduleFileExtensions: [
        "js",
        "vue"
    ],
    moduleNameMapper: {
        "^.+\\.(css|less|scss)$": "identity-obj-proxy"
    }

}