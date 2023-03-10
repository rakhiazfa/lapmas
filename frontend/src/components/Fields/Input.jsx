import React from "react";

const Input = ({
    type,
    label,
    name,
    value,
    onChange,
    placeholder,
    className,
    error,
    rows,
}) => {
    return (
        <div className={`${className} px-2`}>
            <label className="ml-1">{label}</label>
            {type === "textarea" ? (
                <textarea
                    className="field"
                    name={name}
                    value={value}
                    onChange={onChange}
                    placeholder={placeholder}
                    rows={rows ?? "3"}
                ></textarea>
            ) : (
                <input
                    type={type ?? "text"}
                    className="field"
                    name={name}
                    value={value}
                    onChange={onChange}
                    placeholder={placeholder}
                />
            )}
            {error && (
                <p className="text-sm font-normal text-red-500 ml-1 mt-2 -mb-2">
                    {error}
                </p>
            )}
        </div>
    );
};

export default Input;
