import React, { Component } from 'react'
import { NavLink } from 'react-router-dom'
import {Image} from 'antd'

export default function HeaderContent (props) {
  return (
    <header>
        <Image width={200} height={150} alt='main-logo' src='https://placekitten.com/200/150' />
        <NavLink to='/test'>TrickForm</NavLink>
        <NavLink to='/'>Home</NavLink>
    </header>

  )
}