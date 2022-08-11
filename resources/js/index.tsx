import React from 'react';
import ReactDOM from 'react-dom';
import Example from "./components/Example";

const Root = () => {
  return (
    <Example />
  )
}

ReactDOM.render(<Root/>, document.getElementById('root'));
