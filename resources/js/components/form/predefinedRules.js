const predefinedRules = [
    {
        name: 'required',
        func: (value) => {
            if (Array.isArray(value)){
                return value.length > 0 ? true : "Обязательное поле"
            }
            return !!value || "Обязательное поле"
        }
    }
]

export default predefinedRules;