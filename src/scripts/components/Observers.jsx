import React from 'react';

const Observer = ({observer}) => {
    console.log(observer)
  return <li>{observer.url}</li>
};

const Observers = ({observers})=>{
    return <ul>
        {observers.map(o=><Observer key={o.id} observer={o} />)}
    </ul>;
};

export default Observers;