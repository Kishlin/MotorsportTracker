declare type AddMonths = (reference: Date, months: number) => Date;

const addMonths: AddMonths = (reference, months) => {
    const d = reference.getDate();

    const newDate = new Date(reference.getTime());
    newDate.setMonth(reference.getMonth() + +months);

    if (newDate.getDate() !== d) {
        newDate.setDate(0);
    }

    return newDate;
};

export default addMonths;
