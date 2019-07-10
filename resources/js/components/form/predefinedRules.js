
const requiredText = "Обязательное поле"

const predefinedRules = [
    {
        name: 'required',
        func: (value) => {
            if (Array.isArray(value)){
                return value.length > 0 ? true : requiredText
            }
            return !!value || requiredText
        }
    }
]

export default predefinedRules;